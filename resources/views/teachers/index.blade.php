<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>价值派·开户易</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <!-- 微信 JS-SDK -->
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-sm mx-auto bg-white">
        <!-- Header -->
        <header class="flex items-center justify-between px-4 py-3 border-b">
            <div></div>
            <h1 class="text-lg font-semibold">价值派·开户易</h1>
            <button class="text-blue-500">
                <span class="material-icons">more_horiz</span>
            </button>
        </header>

        <!-- Main Content -->
        <main class="divide-y divide-gray-200">
            @forelse($teachers as $teacher)
            <a href="{{ route('teachers.show', $teacher) }}" class="block">
                <div class="p-4 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-start">
                        <img alt="{{ $teacher->name }}" 
                             class="w-16 h-16 rounded-full mr-4" 
                             src="{{ $teacher->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&background=random' }}"/>
                        <div class="flex-1">
                            <div class="flex items-center">
                                <h2 class="text-lg font-semibold">{{ $teacher->name }}</h2>
                                @if($teacher->is_verified_teacher)
                                <span class="ml-2 px-2 py-0.5 text-xs text-yellow-600 bg-yellow-100 rounded-full">已认证</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($teacher->personal_introduction ?? '专业银行卡办理服务', 30) }}</p>
                            
                            @if($teacher->specialties)
                            <div class="flex space-x-2 mt-2">
                                @foreach(explode(',', $teacher->specialties) as $specialty)
                                <span class="px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">{{ trim($specialty) }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="mt-4 space-y-2">
                        @foreach($teacher->services->take(3) as $service)
                        <div class="flex items-center text-sm text-gray-700">
                            <span class="material-icons text-blue-500 mr-2 text-base">rocket_launch</span>
                            {{ $service->service_name }}
                        </div>
                        @endforeach
                    </div>

                    <!-- Stats and Price -->
                    <div class="mt-4 flex justify-between items-center text-sm">
                        <p class="text-gray-500">
                            已帮助 <span class="text-yellow-500 font-semibold">{{ $teacher->completed_orders }}</span> 人 
                            · 评分 <span class="text-yellow-500 font-semibold">{{ number_format($teacher->current_rating, 1) }}</span>
                        </p>
                        @if($teacher->services->count() > 0)
                        <p class="text-yellow-600 font-semibold">
                            <span class="material-icons align-middle text-base -mt-1">monetization_on</span>
                            {{ number_format($teacher->services->min('service_amount'), 0) }} 起
                        </p>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="p-8 text-center text-gray-500">
                <span class="material-icons text-6xl text-gray-300 mb-4">person_search</span>
                <p>暂无老师信息</p>
            </div>
            @endforelse
        </main>

        <!-- Floating Help Button -->
        <div class="fixed bottom-16 right-4">
            <div class="bg-yellow-100 text-yellow-700 text-sm py-2 px-4 rounded-full flex items-center shadow-lg">
                不知道怎么选？欢迎点击我咨询
                <div class="ml-4 bg-yellow-500 w-10 h-10 rounded-full flex items-center justify-center">
                    <span class="material-icons text-white">ring_volume</span>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <footer class="fixed bottom-0 left-0 right-0 max-w-sm mx-auto bg-white border-t flex justify-around py-2">
            <a href="{{ route('teachers.index') }}" class="flex flex-col items-center text-yellow-500">
                <span class="material-icons">home</span>
                <span class="text-xs">首页</span>
            </a>
            <a href="{{ route('profile.show') }}" class="flex flex-col items-center text-gray-400">
                <span class="material-icons">person_outline</span>
                <span class="text-xs">我的</span>
            </a>
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

        // 在小程序中自动给所有链接添加 token
        if (isInMiniProgram()) {
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
        }
    </script>
</body>
</html>