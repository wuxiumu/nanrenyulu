#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"; cd "$ROOT"
fail(){ echo "✗ $1" >&2; exit 1; }; ok(){ echo "✓ $1"; }
command -v php >/dev/null || fail "需要 PHP"
[[ -f admin/config.php && -f api/index.php && -d yijuhua ]] || fail "缺少核心"
[[ -f .env.example ]] || fail "缺少 .env.example"
ok "核心文件齐全"
grep -E "password.*=.*123456|'123456'" admin/config.php && fail "config 仍含硬编码口令" || ok "无硬编码默认口令"
php -l admin/config.php >/dev/null
php -l api/index.php >/dev/null
ok "PHP lint OK"
PORT="${SMOKE_PORT:-18086}"
php -S "127.0.0.1:${PORT}" -t "$ROOT" >/tmp/nanrenyulu-smoke.log 2>&1 &
PID=$!; trap "kill $PID 2>/dev/null||true" EXIT
for i in $(seq 1 30); do curl -sf "http://127.0.0.1:${PORT}/api/" >/dev/null 2>&1 && break; sleep 0.15; done
curl -sf "http://127.0.0.1:${PORT}/api/" >/dev/null || curl -sf "http://127.0.0.1:${PORT}/api/index.php" >/dev/null || fail "API 无响应"
ok "API 可响应"
echo ""; echo "smoke: ok"
