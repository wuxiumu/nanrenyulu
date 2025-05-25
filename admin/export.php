<?php
require 'auth.php';
$config = require 'config.php';
$dir = rtrim($config['data_dir'], '/\\') . '/';

$filename = basename($_GET['file'] ?? '');
$filepath = $dir . $filename;

if (!file_exists($filepath)) {
    die("非法文件");
}
if(!preg_match('/^[\w\-]+\.txt$/', $filename) ){
//     die("非法文件");
}

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="' . $filename . '"');
readfile($filepath);