<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome to Card Base System</h3>
                    
                    @if(auth()->user()->isTeacher())
                        <div class="mb-4">
                            <h4 class="font-semibold">Teacher Dashboard</h4>
                            <p>Manage your services and orders</p>
                            <div class="mt-2">
                                <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded">Manage Services</a>
                                <a href="#" class="bg-green-500 text-white px-4 py-2 rounded ml-2">View Orders</a>
                            </div>
                        </div>
                    @elseif(auth()->user()->isCustomer())
                        <div class="mb-4">
                            <h4 class="font-semibold">Customer Dashboard</h4>
                            <p>Browse teachers and place orders</p>
                            <div class="mt-2">
                                <a href="{{ route('teachers.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">浏览老师</a>
                                <a href="#" class="bg-green-500 text-white px-4 py-2 rounded ml-2">我的订单</a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <p>User Type: <span class="font-semibold">{{ ucfirst(auth()->user()->user_type) }}</span></p>
                    </div>

                    <!-- 小程序交互测试区域 -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg border-2 border-blue-300">
                        <h4 class="font-bold text-lg mb-4 text-blue-600">📱 小程序交互测试</h4>
                        <p class="text-sm text-gray-600 mb-4">以下按钮仅在小程序 WebView 中有效</p>

                        <div class="space-y-3">
                            <!-- 返回测试 -->
                            <div>
                                <button onclick="navigateBackToMiniProgram()"
                                        class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-medium shadow">
                                    ← 返回上一页
                                </button>
                                <span class="text-sm text-gray-500 ml-2">测试返回功能</span>
                            </div>

                            <!-- 跳转到登录页 -->
                            <div>
                                <button onclick="navigateToMiniProgram('/pages/login/index')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium shadow">
                                    🔐 跳转到登录页
                                </button>
                                <span class="text-sm text-gray-500 ml-2">测试页面跳转</span>
                            </div>

                            <!-- 跳转到支付页 -->
                            <div>
                                <button onclick="goToPayment(123, 199.00, '测试订单')"
                                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium shadow">
                                    💰 跳转到支付页 (¥199.00)
                                </button>
                                <span class="text-sm text-gray-500 ml-2">测试支付跳转</span>
                            </div>

                            <!-- 退出登录 -->
                            <div>
                                <button onclick="logoutToMiniProgram()"
                                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium shadow">
                                    🚪 退出登录
                                </button>
                                <span class="text-sm text-gray-500 ml-2">测试登出并返回登录页</span>
                            </div>

                            <!-- 环境检测 -->
                            <div class="mt-4 p-4 bg-white rounded border">
                                <p class="text-sm font-medium">当前环境：</p>
                                <p id="env-status" class="text-lg font-bold text-green-600"></p>
                            </div>
                        </div>
                    </div>

                    <script>
                        // 显示当前环境
                        document.getElementById('env-status').textContent =
                            isInMiniProgram() ? '✅ 小程序 WebView' : '🌐 浏览器';
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
