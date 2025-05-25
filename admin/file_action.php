<?php
require 'auth.php';
$config = require 'config.php';
$dir = $config['data_dir'];

function is_valid_name($name) {
    // 文件名只能包含中文、字母、数字、下划线、中划线，长度 1~50
    if (!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_-]{1,50}$/u', $name)) {
        return false;
    }

    // 防止路径穿越：不能包含 ".."、"/"、"\"
    if (strpos($name, '..') !== false || strpos($name, '/') !== false || strpos($name, '\\') !== false) {
        return false;
    }

    // 禁止隐藏文件（例如 .htaccess）或类似危险文件名
    if ($name[0] === '.' || stripos($name, '.php') !== false) {
        return false;
    }

    return true;
}

$action = $_POST['action'] ?? '';
switch ($action) {
    case 'add':
        $name = trim($_POST['filename'] ?? '');
        if (!is_valid_name($name)) {
            echo "文件名不合法";
            exit;
        }
        $path = $dir . $name . '.txt';
        if (file_exists($path)) {
            echo "文件已存在";
        } else {
            file_put_contents($path, "");
            echo "添加成功";
        }
        break;

    case 'delete':
        $filename = basename($_POST['filename'] ?? '');
        if (!is_valid_name(str_replace('.txt', '', $filename))) {
            echo "文件名不合法";
            exit;
        }
        $path = $dir . $filename;
        if (file_exists($path)) {
            unlink($path);
            echo "删除成功";
        } else {
            echo "文件不存在";
        }
        break;

    case 'rename':
        $old = basename($_POST['old'] ?? '');
        $new = trim($_POST['new'] ?? '');
        // 去掉 .txt 后缀
        $new = str_replace('.txt', '', $new);
        if (!is_valid_name($new)) {
            echo "文件名不合法:".$new;
            exit;
        }
        $oldPath = $dir . $old;
        $newPath = $dir . $new . '.txt';
        if (!file_exists($oldPath)) {
            echo "原文件不存在";
        } elseif (file_exists($newPath)) {
            echo "目标文件已存在";
        } else {
            rename($oldPath, $newPath);
            echo "重命名成功";
        }
        break;

    default:
        echo "无效操作";
}