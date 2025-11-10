<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>我的 - {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto max-w-sm">
        <header class="bg-white text-center py-4 shadow">
            <div class="flex justify-between items-center px-4">
                <div></div>
                <h1 class="text-xl font-semibold text-gray-800">价值派·开户易</h1>
                <button class="text-blue-500">
                    <span class="material-icons">more_horiz</span>
                </button>
            </div>
        </header>

        <main class="mt-4">
            <!-- User Profile Section -->
            <div class="bg-white p-4 flex items-center shadow-sm">
                <img alt="User avatar" 
                     class="w-16 h-16 rounded-full mr-4" 
                     src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"/>
                <div class="flex-1">
                    <div class="flex items-center">
                        <span class="font-semibold text-lg text-gray-800">{{ $user->name }}{{ $user->user_type === 'teacher' ? '老师' : '' }}</span>
                        @if($user->is_verified_teacher)
                        <span class="bg-yellow-100 text-yellow-500 text-xs font-semibold px-2 py-1 rounded-full ml-2">已认证</span>
                        @endif
                    </div>
                </div>
                <a class="text-gray-400 flex items-center" href="{{ route('profile.edit') }}">
                    <span class="text-sm mr-1">个人主页</span>
                    <span class="material-icons text-lg">chevron_right</span>
                </a>
            </div>

            <!-- My Services Section -->
            <div class="mt-4 bg-white shadow-sm">
                {{-- <div class="p-4 border-b border-gray-200 text-gray-600">我的服务</div> --}}
                <ul class="divide-y divide-gray-200">
                    @if($user->user_type === 'teacher')
                    <a href="{{ route('services.my') }}" class="block">
                        <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                            <span class="text-gray-800">我的服务项目</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </li>
                    </a>
                    @endif
                    
                    @if($user->user_type !== 'teacher' && !$user->is_verified_teacher)
                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-blue-800 font-semibold">申请成为开户老师</h3>
                                <p class="text-blue-600 text-sm mt-1">通过认证后，您可以提供开户服务并获得收益</p>
                            </div>
                            <a href="{{ route('teacher.apply') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition-colors">
                                申请
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    <a href="{{ route('orders.customer') }}" class="block">
                        <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                            <span class="text-gray-800">我发起的订单</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </li>
                    </a>
                    @if($user->user_type === 'teacher')
                    <a href="{{ route('orders.teacher') }}" class="block">
                        <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                            <div class="flex items-center">
                                <span class="text-gray-800">我服务的订单</span>
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-sm ml-2">NEW</span>
                            </div>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </li>
                    </a>
                    @endif
                </ul>
            </div>

            <!-- My Assets Section -->
            <div class="mt-4 bg-white shadow-sm">
                {{-- <div class="p-4 border-b border-gray-200 text-gray-600">我的资产</div> --}}
                <ul class="divide-y divide-gray-200">
                    <a href="{{ route('profile.wallet') }}" class="block">
                        <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                            <span class="text-gray-800">我的钱包</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </li>
                    </a>
                    <a href="{{ route('profile.invitations') }}" class="block">
                        <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                            <span class="text-gray-800">我邀请的用户</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </li>
                    </a>
                </ul>
            </div>

            <!-- Settings Section -->
            <div class="mt-4 bg-white shadow-sm">
                {{-- <div class="p-4 border-b border-gray-200 text-gray-600">设置</div> --}}
                <ul class="divide-y divide-gray-200">
                    <a href="{{ route('profile.edit') }}" class="block">
                        <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                            <span class="text-gray-800">账号设置</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </li>
                    </a>
                    <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                        <span class="text-gray-800">意见反馈</span>
                        <span class="material-icons text-gray-400">chevron_right</span>
                    </li>
                    <a href="{{ route('help') }}" class="block">
                        <li class="flex justify-between items-center p-4 hover:bg-gray-50 cursor-pointer">
                            <span class="text-gray-800">帮助</span>
                            <span class="material-icons text-gray-400">chevron_right</span>
                        </li>
                    </a>
                </ul>
            </div>
        </main>

        <!-- Footer Navigation -->
        <footer class="fixed bottom-0 left-0 right-0 max-w-sm mx-auto bg-white shadow-t border-t">
            <div class="flex justify-around py-2">
                <a class="flex flex-col items-center text-gray-500" href="{{ route('teachers.index') }}">
                    <span class="material-icons">home</span>
                    <span class="text-xs">首页</span>
                </a>
                <a class="flex flex-col items-center text-yellow-500" href="{{ route('profile.show') }}">
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