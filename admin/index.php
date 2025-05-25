<?php
require 'auth.php';
$config = require 'config.php';
$dir = $config['data_dir'];

$files = glob($dir . '*.txt');
$fileNames = array_map('basename', $files);
sort($fileNames);

// 分页参数
$perPage = 10;
$total = count($fileNames);
$page = max(1, intval($_GET['page'] ?? 1));
$totalPages = ceil($total / $perPage);
$start = ($page - 1) * $perPage;
$currentFiles = array_slice($fileNames, $start, $perPage);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $config['site_title'] ?></title>
     <link rel="stylesheet" href="./static/style.css" >
     <script src="./static/main.js"></script>
     <meta name="referrer" content="no-referrer">
</head>
<body>
    <h2><?= $config['site_title'] ?> - 文件管理</h2>
    <p><img src="<?= $config['site_logo'] ?>" height="50"> <a href="logout.php">[退出登录]</a></p>

    <!-- 添加新文件 -->
    <form id="addForm" style="margin-bottom:20px;">
        <input type="text" name="filename" placeholder="新文件名（无需.txt）" required>
        <button type="submit">添加</button>
        <span id="addMsg"></span>
    </form>

    <!-- 文件列表 -->
    <div id="fileList">
        <?php foreach ($currentFiles as $file): ?>
            <div class="file-item quote" data-file="<?= htmlspecialchars($file) ?>">
                <a href="module.php?file=<?= urlencode($file) ?>" target="_blank"><?= htmlspecialchars($file) ?></a>
                <span class="actions quote-buttons">
                    <button class="btn renameBtn">重命名</button>
                    <button class="btn deleteBtn">删除</button>
                    <button class="btn moduleBtnBtn">语录编辑</button>
                </span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 分页 -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">« 上一页</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?= $i == $page ? "<strong>$i</strong>" : '<a href="?page=' . $i . '">' . $i . '</a>' ?>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">下一页 »</a>
        <?php endif; ?>
    </div>

    <script>
    // 编辑模块 跳转module.php?file=文件名
    document.querySelectorAll(".moduleBtnBtn").forEach(btn => {
        btn.onclick = function() {
            const file = this.closest(".file-item").dataset.file;
            location.href = "module.php?file=" + encodeURIComponent(file);
        };
    });
    // 添加文件
    document.getElementById("addForm").onsubmit = function(e) {
        e.preventDefault();
        const name = this.filename.value.trim();
        if (!name) return;

        fetch("file_action.php", {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: "action=add&filename=" + encodeURIComponent(name)
        }).then(r => r.text()).then(msg => {
            document.getElementById("addMsg").textContent = msg;
            location.reload();
        });
    };

    // 删除文件
    document.querySelectorAll(".deleteBtn").forEach(btn => {
        btn.onclick = function() {
            const file = this.closest(".file-item").dataset.file;
            if (confirm("确定要删除 " + file + " 吗？")) {
                fetch("file_action.php", {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: "action=delete&filename=" + encodeURIComponent(file)
                }).then(r => r.text()).then(msg => {
                    showDialog(msg);
                    location.reload();
                });
            }
        };
    });

    // 重命名文件
    document.querySelectorAll(".renameBtn").forEach(btn => {
        btn.onclick = function() {
            const div = this.closest(".file-item");
            const oldName = div.dataset.file;
            // 生成一个剧中弹窗表单，让用户修改内容
            const form = document.createElement("form");
            form.innerHTML = `
                <input type="text" name="newName" value="${oldName.replace('.txt', '')}" required>
                <button type="submit">确定</button>
                <button type="button" onclick="document.body.removeChild(this.parentNode)">取消</button>
            `;
            form.onsubmit = function(e) {
                e.preventDefault();
                const newName = this.newName.value.trim();
                if (!newName) return;
                fetch("file_action.php", {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: "action=rename&old=" + encodeURIComponent(oldName) + "&new=" + encodeURIComponent(newName + '.txt')
                }).then(r => r.text()).then(msg => {
                    showDialog(msg);
                    location.reload();
                });
                document.body.removeChild(this);
            };
            document.body.appendChild(form);
            // 弹窗表单的样式
            form.style.position = "fixed";
            form.style.top = "50%";
            form.style.left = "50%";
            form.style.transform = "translate(-50%, -50%)";
            form.style.padding = "20px";
            form.style.backgroundColor = "white";
            form.style.border = "1px solid #ccc";
            form.style.borderRadius = "5px";
            form.style.boxShadow = "0 0 10px rgba(0,0,0,0.3)";
            form.style.zIndex = "9999";
            // 弹窗表单的输入框样式
            const input = form.querySelector("input");
            input.style.width = "300px";
            input.style.marginBottom = "10px";
            input.focus();   // 自动聚焦输入框
        };
    });
    </script>
</body>
</html>