<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>帮助中心 - 价值派·开户易</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <!-- 微信 JS-SDK -->
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto max-w-sm pb-20">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="flex justify-between items-center px-4 py-3">
                <button onclick="navigateBackToMiniProgram()" class="text-gray-600">
                    <span class="material-icons">arrow_back</span>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">帮助中心</h1>
                <div class="w-6"></div>
            </div>
        </header>

        <main class="mt-4 px-4 space-y-4">
            <!-- 常见问题 -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800 flex items-center">
                        <span class="material-icons text-blue-500 mr-2">help_outline</span>
                        常见问题
                    </h2>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-2">如何选择开户老师？</h3>
                        <p class="text-sm text-gray-600">在首页浏览老师列表，查看老师的评分、服务次数和专业领域，选择适合您需求的老师。</p>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-2">如何下单？</h3>
                        <p class="text-sm text-gray-600">进入老师详情页，选择所需服务，点击"立即预约"填写信息并提交订单。</p>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-2">如何支付？</h3>
                        <p class="text-sm text-gray-600">老师接单后，系统会引导您进入支付页面，支持微信支付。</p>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-2">如何申请成为开户老师？</h3>
                        <p class="text-sm text-gray-600">在"我的"页面点击"申请成为开户老师"，填写相关资料提交审核。</p>
                    </div>
                </div>
            </div>

            <!-- 联系客服 -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h2 class="font-semibold text-gray-800 flex items-center mb-3">
                    <span class="material-icons text-green-500 mr-2">support_agent</span>
                    联系客服
                </h2>
                <p class="text-sm text-gray-600 mb-3">如有其他问题，欢迎联系我们的客服团队</p>
                <button class="w-full bg-green-500 text-white py-3 rounded-lg font-medium hover:bg-green-600 transition-colors flex items-center justify-center">
                    <span class="material-icons mr-2">chat</span>
                    在线客服
                </button>
            </div>

            <!-- 使用指南 -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800 flex items-center">
                        <span class="material-icons text-yellow-500 mr-2">menu_book</span>
                        使用指南
                    </h2>
                </div>
                <div class="divide-y divide-gray-200">
                    <a href="#" class="block p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-800">新手入门</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </div>
                    </a>
                    <a href="#" class="block p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-800">订单流程</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </div>
                    </a>
                    <a href="#" class="block p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-800">安全保障</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- 关于我们 -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h2 class="font-semibold text-gray-800 flex items-center mb-3">
                    <span class="material-icons text-gray-500 mr-2">info</span>
                    关于我们
                </h2>
                <p class="text-sm text-gray-600 mb-2">价值派·开户易</p>
                <p class="text-xs text-gray-500">版本号：v1.0.0</p>
            </div>

            <!-- 小程序测试区域 -->
            <div id="miniprogram-test" class="hidden bg-blue-50 border-2 border-blue-400 rounded-lg p-4 shadow-sm">
                <div class="space-y-2">
                    <button onclick="navigateBackToMiniProgram()"
                            class="w-full bg-purple-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-purple-600 transition-colors">
                        ← 返回上一页
                    </button>
                    <button onclick="goToPayment(123, 199.00, '测试订单')"
                            class="w-full bg-green-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-green-600 transition-colors">
                        💰 测试支付 (¥199.00)
                    </button>
                    <button onclick="logoutToMiniProgram()"
                            class="w-full bg-red-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors">
                        🚪 退出登录
                    </button>
                </div>
            </div>
        </main>

        <!-- Footer Navigation -->
        <footer class="fixed bottom-0 left-0 right-0 max-w-sm mx-auto bg-white shadow-t border-t">
            <div class="flex justify-around py-2">
                <a class="flex flex-col items-center text-gray-500" href="{{ route('teachers.index') }}">
                    <span class="material-icons">home</span>
                    <span class="text-xs">首页</span>
                </a>
                <a class="flex flex-col items-center text-gray-500" href="{{ route('profile.show') }}">
                    <span class="material-icons">person</span>
                    <span class="text-xs">我的</span>
                </a>
            </div>
        </footer>
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

        // 退出登录
        function logoutToMiniProgram() {
            if (isInMiniProgram()) {
                wx.miniProgram.postMessage({
                    data: { action: 'logout' }
                })
                wx.miniProgram.reLaunch({
                    url: '/pages/login/index'
                })
            } else {
                window.location.href = '/logout'
            }
        }

        // 显示测试区域和环境状态
        if (isInMiniProgram()) {
            document.getElementById('miniprogram-test').classList.remove('hidden')
            document.getElementById('env-status').textContent = '✅ 小程序 WebView'

            // 自动给所有链接添加 token
            const token = new URLSearchParams(window.location.search).get('token')
            if (token) {
                document.addEventListener('DOMContentLoaded', function() {
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
        } else {
            document.getElementById('env-status').textContent = '🌐 浏览器'
        }
    </script>
</body>
</html>
