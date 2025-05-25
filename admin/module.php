<?php
require 'auth.php'; // 登录验证
$config = require 'config.php';
$dir = rtrim($config['data_dir'], '/\\') . '/';

$filename = basename($_GET['file'] ?? '');
$filepath = $dir . $filename;

// 安全校验
if (!file_exists($filepath)) {
    die("非法文件");
}
if(!preg_match('/^[\w\-]+\.txt$/', $filename) ){
//     die("非法文件");
}

// 加载内容
// 先判断文件空行数，如果只有一行，则直接显示内容，否则显示分页

$content = file_get_contents($filepath);
$paragraphs = preg_split('/\R{2,}/', $content, -1, PREG_SPLIT_NO_EMPTY);
if (count($paragraphs) > 2) {
    $lines = $paragraphs;
    echo "<style>.editBtn { display: none; } .delBtn{ display: none; } #addForm{ display: none; }</style>";
}else{
    // 根据换行分割数组
    $lines = explode("\n", $content);
}

// $lines = file($filepath, FILE_IGNORE_NEW_LINES); // 读取文件内容
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

// 过滤搜索
$filtered = [];
foreach ($lines as $i => $line) {
    if ($search === '' || stripos($line, $search) !== false) {
        $filtered[] = ['index' => $i, 'text' => $line];
    }
}
$total = count($filtered);
$totalPages = ceil($total / $perPage);
$display = array_slice($filtered, ($page - 1) * $perPage, $perPage);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($filename) ?> - 编辑内容</title>
     <link rel="stylesheet" href="./static/style.css" >
     <script src="./static/main.js"></script>
     <meta name="referrer" content="no-referrer">
</head>
<body>
<h2><?= htmlspecialchars($filename) ?> - 内容管理</h2>
<p><a href="index.php">← 返回文件列表</a></p>

<div class="stats">
    总语录数：<?= count($lines) ?>，当前显示：<?= $total ?> 条<?= $search ? "（含搜索「<strong>" . htmlspecialchars($search) . "</strong>」）" : "" ?>
</div>

<p>
    <a class="btn" href="export.php?file=<?= urlencode($filename) ?>" target="_blank">导出为 TXT</a>
</p>


<form method="get">
    <input type="hidden" name="file" value="<?= htmlspecialchars($filename) ?>">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="搜索关键词">
    <button type="submit">搜索</button>
</form>

<form id="addForm" style="margin-top:20px;">
    <input type="text" id="newText" placeholder="添加新语句" required style="width:300px;">
    <button type="submit">添加</button>
</form>

<div id="content">
<?php foreach ($display as $row):
    $text = htmlspecialchars($row['text']);
    if ($search !== '') {
        $text = preg_replace("/(" . preg_quote($search, '/') . ")/i", '<span class="highlight">$1</span>', $text);
    }
?>
    <div class="line" data-index="<?= $row['index'] ?>">
        <span class="text"><?= $text ?></span>
        <button class="btn btn-copy">复制</button>
        <button class="btn editBtn">编辑</button>
        <button class="btn btn-danger delBtn">删除</button>
    </div>
<?php endforeach; ?>
</div>

<!-- 分页 -->
<div class="pagination" style="margin-top: 20px;">
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?= ($i == $page) ? "<strong>$i</strong>" : '<a href="?file=' . urlencode($filename) . '&search=' . urlencode($search) . '&page=' . $i . '">' . $i . '</a>' ?>
<?php endfor; ?>
</div>

<script>
document.querySelectorAll(".btn-copy").forEach(btn => {
    btn.onclick = function() {
        let text = this.parentNode.querySelector(".text").textContent;
        navigator.clipboard.writeText(text).then(() => showDialog("已复制"));
    };
});
document.querySelectorAll(".editBtn").forEach(btn => {
    btn.onclick = function() {
        let div = this.parentNode;
        let index = div.dataset.index;
        let oldText = div.querySelector(".text").textContent;
            // 生成一个剧中弹窗表单，让用户修改内容
            let form = document.createElement("form");
            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="file" value="<?= htmlspecialchars($filename) ?>">
                <input type="hidden" name="index" value="${index}">
                <textarea rows="10" cols="30" name="text" required>${oldText}</textarea>
                <button type="submit">保存</button>
            `;
            let dialog = document.createElement("dialog");
            dialog.appendChild(form);
            document.body.appendChild(dialog);
            dialog.showModal();
            form.onsubmit = function(e) {
                e.preventDefault();
                let data = new FormData(form);
                fetch("module_action.php", {
                    method: "POST",
                    body: data
                }).then(r => r.text()).then(msg => {
                    showDialog(msg);
                    location.reload();
                });
                dialog.close();
            }
    };
});

document.querySelectorAll(".delBtn").forEach(btn => {
    btn.onclick = function() {
        if (!confirm("确定要删除这条语录？")) return;
        let div = this.parentNode;
        let index = div.dataset.index;
        fetch("module_action.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "action=delete&file=<?= urlencode($filename) ?>&index=" + index
        }).then(r => r.text()).then(msg => {
            showDialog(msg);
            location.reload();
        });
    };
});

document.getElementById("addForm").onsubmit = function(e) {
    e.preventDefault();
    let text = document.getElementById("newText").value.trim();
    if (!text) return;
    fetch("module_action.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=add&file=<?= urlencode($filename) ?>&text=" + encodeURIComponent(text)
    }).then(r => r.text()).then(msg => {
        showDialog(msg);
        location.reload();
    });
};

</script>
</body>
</html>