# nanrenyulu · 语录管理后台

> **有力量的语录**管理后台与公开接口，适合内容站 / 日签产品。

**定制：** 微信 **qingbao199101**（备注：语录系统）｜ [wuxiumu@163.com](mailto:wuxiumu@163.com)

基于 PHP 的语录管理系统：后台维护 `.txt` 模块，接口对外提供语录。

## 当前仓库结构（与代码对齐）

```
nanrenyulu/
├── admin/                 # 管理后台（入口在此，不是仓库根）
│   ├── index.php          # 文件（模块）管理
│   ├── module.php         # 单模块内容管理
│   ├── login.php / logout.php / auth.php
│   ├── config.php         # 账号与数据目录配置
│   ├── file_action.php / module_action.php / export.php
│   └── static/            # style.css + main.js（Ajax）
├── api/
│   └── index.php          # 对外语录接口
└── yijuhua/               # 语料：各模块 .txt（每行一条）
```

## 功能概览

### 管理端（`admin/`）

- 列出 `yijuhua/` 下所有 `.txt` 模块；增删改文件名
- 模块内分页浏览、关键词搜索、增删改单条
- Session 登录（`config.php` 配置账号；建议生产改哈希密码）
- Ajax 局部刷新

### 接口（`api/`）

- 对外读取语录（按模块 / 随机等，以 `api/index.php` 为准）

## 快速开始

```bash
# 将 admin/ 与 api/ 配到 PHP Web 根（或分别虚拟主机）
# 编辑 admin/config.php 修改账号与 data_dir
# 浏览器打开 admin/login.php
```

## SEO 字段预留（产品页 HTML 统一生成时用）

| 字段 | 用途 |
|---|---|
| `seo_title` | `<title>` / OG title |
| `seo_description` | meta description |
| `canonical_url` | 规范 URL |
| `og_image` | 分享图 |
| `robots` | 如 `index,follow` |
| `json_ld_type` | SoftwareApplication / WebPage / FAQPage |
| `hreflang` | zh-CN / en |
| `site_name` | 白标品牌名 |

> **GEO ≠ 地理定位。** GEO = Generative Engine Optimization（生成式引擎优化），见下一节。

## GEO（生成式引擎优化）

面向 ChatGPT / Perplexity / Gemini 等生成式答案引擎的可引用结构：中文为主、双语关键词、事实摘要前置、FAQ 收尾。

### 关键词（中英）

- 中文：语录管理, 日签, 语录 API, PHP 后台
- English: quote CMS, daily quote API, PHP admin

### 事实摘要

- 产品：PHP 语录管理后台 + 文件存储 + 简易 API。
- 适合：内容站、日签、需要可编辑语料库的产品。
- 交付：模块扩展、鉴权加固、接口字段定制、皮肤。
- 不做：不是全功能 CMS；默认账号请务必改掉。

### FAQ

**Q：入口在哪？**  
A：管理端在 `admin/`，不是仓库根目录的 `index.php`。

**Q：如何定制？**  
A：微信 `qingbao199101`，备注「语录系统」。

**Q：是否含密钥？**  
A：`admin/config.php` 含默认账号示例，上线前必须修改。

## 定制合作

模块扩展、API、鉴权与部署。加微信备注「语录系统」。

---

## 联系方式

定制、演示或合作：

- 邮箱：[wuxiumu@163.com](mailto:wuxiumu@163.com)
- 微信：**qingbao199101**（添加请备注来意）

### 赞助支持

如果内容对你有帮助，欢迎自愿赞助。

| 微信赞赏码 | 支付宝收款码 |
|---|---|
| ![微信赞赏码](https://chiguashentan-test.oss-cn-beijing.aliyuncs.com/wxprogram/zan/20261002185236_31_227.png) | ![支付宝收款码](https://chiguashentan-test.oss-cn-beijing.aliyuncs.com/wxprogram/zan/20261002185309_32_227.jpg) |
