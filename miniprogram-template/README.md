# Cardbase 小程序快速启动模板

这是一个极简的小程序模板，只包含 3 个页面：登录、支付、WebView。

## 使用步骤

### 1. 导入项目到微信开发者工具

1. 打开微信开发者工具
2. 选择"导入项目"
3. 选择 `miniprogram-template` 文件夹
4. 填写你的小程序 AppID

### 2. 修改配置

打开 `app.js`，修改域名配置：

```javascript
globalData: {
  apiBase: 'https://yourdomain.com/api/v1',  // 改为你的 API 域名
  webBase: 'https://yourdomain.com',         // 改为你的网站域名
}
```

### 3. 配置小程序后台

在微信小程序后台配置：

**开发 > 开发管理 > 开发设置**

1. **服务器域名（request 合法域名）**
   - `https://yourdomain.com`

2. **业务域名**
   - `https://yourdomain.com`
   - 下载校验文件并放到 Laravel 的 `public` 目录

### 4. 完成 Laravel 适配

参考 `小程序开发指南.md` 中的 Laravel 适配部分。

### 5. 开始测试

1. 在开发者工具中点击"编译"
2. 输入测试账号登录
3. 登录后会自动跳转到 WebView 加载 Laravel 首页

## 项目结构

```
miniprogram-template/
├── app.js              # 全局配置
├── app.json            # 页面配置
├── pages/
│   ├── login/          # 登录页面（需要完善样式）
│   ├── payment/        # 支付页面（需要完善样式和支付逻辑）
│   └── webview/        # WebView 通用页面（已完成）
└── README.md
```

## 待完成功能

- [ ] 登录页面样式美化
- [ ] 支付页面样式美化
- [ ] 集成微信支付 API
- [ ] 添加错误处理
- [ ] 添加 loading 提示

## 注意事项

1. 必须使用 HTTPS 域名
2. 业务域名需要下载校验文件
3. 小程序只能访问配置过的域名
4. WebView 有一些限制，详见微信官方文档

## 测试账号

- 邮箱：teacher1@cardbase.com
- 密码：（需要重置）

---

快速上线，后续优化！
