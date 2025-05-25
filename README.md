
# nanrenyulu 语录管理系统
闲来无聊，设计一个语录管理后台和接口。有力量的语录


## ✅ 项目目标概览

一个基于 PHP 的语录管理系统，核心功能如下：

### 1️⃣ 文件管理页（首页）
```
•	列出 yijuhua/ 目录下所有 .txt 文件（即“模块”）
•	✅ 支持分页浏览
•	✅ 支持添加新文件
•	✅ 支持编辑文件名
•	✅ 支持删除文件（有弹窗确认）
•	✅ 每个文件名称是一个链接，点击进入内容编辑模块（下方第2条）
```
### 2️⃣ 内容管理页（module.php）
```
•	展示选中文件中的内容（每行一条）
•	✅ 支持分页浏览
•	✅ 支持关键词搜索（关键词高亮）
•	✅ 支持编辑、删除单条记录（删除确认）
•	✅ 支持添加新语句
•	✅ 所有操作通过 Ajax 局部刷新（无整页刷新）
```
### 3️⃣ 登录权限控制
```
•	登录校验
•	✅ 登录后进入系统
•	✅ 未登录则跳转到登录页
•	✅ 登录状态通过 session 维持
•	✅ 登录表单使用 Ajax 提交
```
### 4️⃣ config.php 配置文件

集中配置以下内容：
```
<?php
return [
    'username' => 'admin',
    'password' => '123456', // 建议后续使用哈希
    'data_dir' => __DIR__ . '/yijuhua/',
    'site_title' => '语录管理系统',
    'site_logo' => 'logo.png', // 相对路径
];
```


⸻

## 🧩 文件结构
```
/项目目录/
│
├── index.php           // 文件管理页
├── module.php          // 内容管理页
├── login.php           // 登录页
├── logout.php          // 登出处理
├── config.php          // 配置项
├── yijuhua/            // 所有.txt文件数据目录
├── static/
│   ├── style.css
│   └── main.js         // 放 Ajax 与弹窗等逻辑
└── assets/logo.png     // logo 图片
```


⸻

## 🧠 技术点清单
```
功能	技术或方式
文件/内容分页	PHP 后端分页
搜索关键词高亮	JS 高亮 + PHP 搜索过滤
删除确认弹窗	JS confirm() 或 Modal 模板
Ajax 局部刷新	jQuery 或原生 fetch() + JSON
登录校验	$_SESSION 维持登录状态
页面跳转校验	登录检查在 auth.php 或统一 header 文件中实现
配置文件独立	使用 config.php return []

```

⸻

## 模块划分整源码

```
	•	🔐 登录系统
	•	📂 文件列表页（index.php）
	•	📝 内容编辑页（module.php）
	•	⚙️ config.php + 公共函数 utils.php
	•	💡 Ajax + JS 管理
```

## 预览

### 登陆页：
<img src="https://archive.biliimg.com/bfs/archive/216a5958b2bccb5b9acac75dda685cba32f10622.png" alt="登陆" referrerpolicy="no-referrer">

### 语录文件列表页：

![](https://archive.biliimg.com/bfs/archive/e40f0dad0e25906d3bf490c2eb2b43c85f2a4a28.png)
### 语录内容列表页
![](https://archive.biliimg.com/bfs/archive/38205f11a9b7b3858d5715d019179e197f4bc04c.png)
### 编辑语录页 
![](https://archive.biliimg.com/bfs/archive/ae672d6f13eab0d65766dfb87b5df7290759d070.png)