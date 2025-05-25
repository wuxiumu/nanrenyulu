<?php
require 'auth.php';
$config = require 'config.php';
$dir = rtrim($config['data_dir'], '/\\') . '/';

$action = $_POST['action'] ?? '';
$filename = basename($_POST['file'] ?? '');
$filepath = $dir . $filename;

if (!file_exists($filepath)) {
    die("非法文件");
}
if(!preg_match('/^[\w\-]+\.txt$/', $filename) ){
//     die("非法文件");
}

$lines = file($filepath, FILE_IGNORE_NEW_LINES);

switch ($action) {
    case 'edit':
        $index = intval($_POST['index'] ?? -1);
        $text = trim($_POST['text'] ?? '');
        if (!isset($lines[$index])) die("行号无效");
        $lines[$index] = $text;
        file_put_contents($filepath, implode("\n", $lines));
        echo "修改成功";
        break;

    case 'delete':
        $index = intval($_POST['index'] ?? -1);
        if (!isset($lines[$index])) die("行号无效");
        unset($lines[$index]);
        file_put_contents($filepath, implode("\n", $lines));
        echo "删除成功";
        break;

    case 'add':
        $text = trim($_POST['text'] ?? '');
        if ($text === '') die("内容不能为空");
        $lines[] = $text;
        file_put_contents($filepath, implode("\n", $lines));
        echo "添加成功";
        break;

    default:
        echo "无效操作";
}