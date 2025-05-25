<?php
session_start();
$config = require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUser = $_POST['username'] ?? '';
    $inputPass = $_POST['password'] ?? '';
    if ($inputUser === $config['username'] && $inputPass === $config['password']) {
        $_SESSION['logged_in'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '账号或密码错误']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>登录 - <?= $config['site_title'] ?></title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 100px; }
        form { display: inline-block; text-align: left; }
        input { display: block; margin: 10px 0; padding: 5px; width: 200px; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <img src="<?= $config['site_logo'] ?>" height="80" alt="logo">
    <h2><?= $config['site_title'] ?></h2>
    <form id="loginForm">
        <input type="text" name="username" placeholder="用户名" required>
        <input type="password" name="password" placeholder="密码" required>
        <button type="submit">登录</button>
        <div class="error" id="errorMsg"></div>
    </form>

    <script>
        document.getElementById("loginForm").onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch("login.php", {
                method: "POST",
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    window.location.href = "index.php";
                } else {
                    document.getElementById("errorMsg").textContent = data.message;
                }
            });
        };
    </script>
</body>
</html>