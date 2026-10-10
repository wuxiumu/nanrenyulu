<?php
/**
 * 语录后台配置。口令请用环境变量，勿提交真实密码。
 * ADMIN_USERNAME / ADMIN_PASSWORD
 */
return [
    'username' => getenv('ADMIN_USERNAME') ?: 'admin',
    'password' => getenv('ADMIN_PASSWORD') ?: '', // 空则拒绝弱默认口令，须在环境设置 ADMIN_PASSWORD
    'data_dir' => getenv('YIJUHUA_DATA_DIR') ?: '../yijuhua/',
    'site_title' => '语录管理系统',
    'site_logo' => 'https://archive.biliimg.com/bfs/archive/b79a437ac0b60d628279aca1dc7ba9ec9a059f7d.jpg',
];
