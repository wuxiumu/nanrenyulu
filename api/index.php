<?php
$config = require '../admin/config.php';
$dir = $config['data_dir'];
// 默认
$filename = basename($_GET['file'] ?? '');
if (!$filename) {
    $filename = '男人语录';
}
// 自动补全文件后缀
if (strpos($filename, '.') === false) {
    $filename.= '.txt';
}
$filepath = $dir. $filename;

if (file_exists($filepath)) {
    // 获取文件内容
    $content = file_get_contents($filepath);
    $paragraphs = preg_split('/\R{2,}/', $content, -1, PREG_SPLIT_NO_EMPTY); // 正则分割段落
    if (count($paragraphs) > 2) {
        $lines = $paragraphs;
    }else{
        // 根据换行分割数组
        $lines = explode("\n", $content);
    }
    // 随机取一条
  $line = $lines[array_rand($lines)];

    //输出
    echo $line;
    exit;
} else {
    header('HTTP/1.1 404 Not Found');
    echo 'File not found';
}