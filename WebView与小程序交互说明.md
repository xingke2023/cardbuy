# WebView 与小程序交互说明

## 📱 可用的 JavaScript 函数

Laravel 页面中已经内置了以下函数，可以直接在 WebView 中调用：

### 1. 返回上一页
```javascript
navigateBackToMiniProgram()
```

**使用场景**：点击返回按钮时

**示例**：
```html
<button onclick="navigateBackToMiniProgram()">返回</button>
```

---

### 2. 跳转到小程序指定页面
```javascript
navigateToMiniProgram('/pages/xxx/index')
```

**使用场景**：从 WebView 跳转到小程序原生页面

**示例**：
```html
<!-- 跳转到支付页面 -->
<button onclick="navigateToMiniProgram('/pages/payment/index?orderId=123')">
  去支付
</button>

<!-- 跳转到登录页 -->
<button onclick="navigateToMiniProgram('/pages/login/index')">
  去登录
</button>
```

---

### 3. 跳转到支付页面（便捷方法）
```javascript
goToPayment(orderId, amount, description)
```

**参数**：
- `orderId`: 订单 ID
- `amount`: 金额（数字）
- `description`: 订单描述（可选）

**示例**：
```html
<button onclick="goToPayment(123, 199.00, '订单支付')">
  支付 ¥199.00
</button>
```

---

### 4. 退出登录
```javascript
logoutToMiniProgram()
```

**使用场景**：用户点击退出登录按钮

**示例**：
```html
<button onclick="logoutToMiniProgram()">退出登录</button>
```

---

## 🎯 实际使用示例

### 示例 1：订单详情页面

```blade
<!-- resources/views/orders/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="order-detail">
    <h1>订单详情</h1>

    <div class="order-info">
        <p>订单号：{{ $order->id }}</p>
        <p>金额：¥{{ $order->payment_amount }}</p>
        <p>状态：{{ $order->status }}</p>
    </div>

    @if($order->status === 'accepted' && !$order->is_paid)
    <!-- 去支付按钮 -->
    <button onclick="goToPayment({{ $order->id }}, {{ $order->payment_amount }}, '订单支付')">
        立即支付
    </button>
    @endif

    <!-- 返回按钮 -->
    <button onclick="navigateBackToMiniProgram()">
        返回
    </button>
</div>
@endsection
```

---

### 示例 2：个人中心页面

```blade
<!-- resources/views/profile/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="profile">
    <h1>个人中心</h1>

    <div class="user-info">
        <p>姓名：{{ Auth::user()->name }}</p>
        <p>邮箱：{{ Auth::user()->email }}</p>
    </div>

    <div class="menu">
        <a href="/my/orders?token={{ request('token') }}">我的订单</a>
        <a href="/my/services?token={{ request('token') }}">我的服务</a>
    </div>

    <!-- 退出登录 -->
    <button onclick="logoutToMiniProgram()">
        退出登录
    </button>
</div>
@endsection
```

---

### 示例 3：列表页面（带返回）

```blade
<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="orders-list">
    <!-- 自定义导航栏（因为小程序中会隐藏 Laravel 导航栏） -->
    <div class="custom-navbar">
        <button onclick="navigateBackToMiniProgram()" class="back-btn">
            ← 返回
        </button>
        <h1>我的订单</h1>
    </div>

    <div class="list">
        @foreach($orders as $order)
        <div class="order-item">
            <a href="/orders/{{ $order->id }}?token={{ request('token') }}">
                <p>订单 #{{ $order->id }}</p>
                <p>¥{{ $order->payment_amount }}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
```

---

## 🔧 自动功能

### 1. 自动隐藏导航栏
在小程序 WebView 中，Laravel 的顶部导航栏会自动隐藏，因为小程序有自己的导航栏。

### 2. 自动添加 Token
所有站内链接会自动添加 `token` 参数，保持登录状态。

**例如**：
```html
<a href="/orders/123">订单详情</a>
```

**会自动变成**：
```html
<a href="/orders/123?token=xxx">订单详情</a>
```

### 3. 检测环境
`isInMiniProgram()` 函数可以检测当前是否在小程序中：

```javascript
if (isInMiniProgram()) {
    // 在小程序中的特殊处理
    console.log('运行在小程序中')
} else {
    // Web 端的处理
    console.log('运行在浏览器中')
}
```

---

## 📝 在 Blade 模板中使用

### 条件渲染（根据环境显示不同内容）

```blade
<script>
    if (isInMiniProgram()) {
        // 小程序中显示返回按钮
        document.write('<button onclick="navigateBackToMiniProgram()">返回</button>')
    } else {
        // Web 端显示浏览器返回
        document.write('<button onclick="window.history.back()">返回</button>')
    }
</script>
```

---

## 🎨 样式建议

在小程序中，建议添加一些特殊样式：

```css
/* 为小程序 WebView 优化的样式 */
.custom-navbar {
    position: sticky;
    top: 0;
    background: #fff;
    padding: 16rpx;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #eee;
    z-index: 100;
}

.back-btn {
    background: none;
    border: none;
    font-size: 32rpx;
    color: #333;
    cursor: pointer;
}
```

---

## 🔄 WebView 与小程序通信流程

### 从 WebView 跳转到小程序

```
WebView 页面
     ↓
调用 navigateToMiniProgram('/pages/xxx')
     ↓
小程序接收并跳转到对应页面
```

### 从小程序跳转到 WebView

```javascript
// 小程序代码
wx.navigateTo({
  url: '/pages/webview/index?url=' +
       encodeURIComponent('https://yourdomain.com/orders/123?token=' + token)
})
```

---

## ⚠️ 注意事项

1. **URL 编码**：跳转时要对 URL 进行编码
2. **Token 传递**：确保所有 WebView URL 都带上 token 参数
3. **支付限制**：支付功能必须在小程序原生页面中完成
4. **导航栏**：WebView 中不要依赖 Laravel 的导航栏，建议自己实现
5. **返回逻辑**：使用 `navigateBackToMiniProgram()` 而不是 `history.back()`

---

## 🧪 测试清单

- [ ] 点击返回按钮能正常返回
- [ ] 点击支付按钮能跳转到支付页面
- [ ] 退出登录能跳转到登录页面
- [ ] 所有链接都自动带上 token
- [ ] Laravel 导航栏在小程序中隐藏
- [ ] WebView 中的所有功能正常工作

---

## 📚 相关文档

- [微信小程序 WebView 组件文档](https://developers.weixin.qq.com/miniprogram/dev/component/web-view.html)
- [微信 JS-SDK 文档](https://developers.weixin.qq.com/miniprogram/dev/component/web-view.html#%E7%9B%B8%E5%85%B3%E6%8E%A5%E5%8F%A3-4)

---

**现在你的 WebView 页面可以自由地与小程序交互了！** 🎉
