<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- 微信 JS-SDK -->
        <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- 小程序适配脚本 -->
        <script>
            // 检测是否在小程序 WebView 中
            function isInMiniProgram() {
                return window.__wxjs_environment === 'miniprogram'
            }

            // 返回小程序上一页
            function navigateBackToMiniProgram() {
                if (isInMiniProgram()) {
                    wx.miniProgram.navigateBack()
                } else {
                    window.history.back()
                }
            }

            // 跳转到小程序指定页面
            function navigateToMiniProgram(url) {
                if (isInMiniProgram()) {
                    wx.miniProgram.navigateTo({ url: url })
                }
            }

            // 跳转到支付页面
            function goToPayment(orderId, amount, desc) {
                if (isInMiniProgram()) {
                    wx.miniProgram.navigateTo({
                        url: '/pages/payment/index?orderId=' + orderId +
                             '&amount=' + amount +
                             '&desc=' + encodeURIComponent(desc || '订单支付')
                    })
                } else {
                    alert('请在小程序中使用支付功能')
                }
            }

            // 退出登录，跳转到小程序登录页
            function logoutToMiniProgram() {
                if (isInMiniProgram()) {
                    // 清除本地存储
                    wx.miniProgram.postMessage({
                        data: { action: 'logout' }
                    })
                    // 跳转到登录页
                    wx.miniProgram.reLaunch({
                        url: '/pages/login/index'
                    })
                } else {
                    // Web 端退出
                    window.location.href = '/logout'
                }
            }

            // 在小程序中自动给所有链接添加 token 参数
            if (isInMiniProgram()) {
                const token = new URLSearchParams(window.location.search).get('token')

                if (token) {
                    document.addEventListener('DOMContentLoaded', function() {
                        // 给所有链接添加 token
                        const links = document.querySelectorAll('a')
                        links.forEach(link => {
                            const href = link.getAttribute('href')
                            if (href && !href.startsWith('http') && !href.includes('token=') && !href.startsWith('#')) {
                                const separator = href.includes('?') ? '&' : '?'
                                link.setAttribute('href', href + separator + 'token=' + token)
                            }
                        })
                    })
                }

                // 隐藏顶部导航栏（小程序有自己的导航）
                const style = document.createElement('style')
                style.innerHTML = `
                    /* 隐藏 Laravel 导航栏 */
                    nav { display: none !important; }
                    /* 调整主内容区域 */
                    main { padding-top: 0 !important; }
                `
                document.head.appendChild(style)
            }
        </script>
    </body>
</html>
