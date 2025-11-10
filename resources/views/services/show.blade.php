<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>我的服务详情</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            background-color: #f0f2f5;
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white shadow-lg">
        <header class="flex items-center justify-between p-4 bg-white border-b">
            <div class="flex items-center">
                <a href="{{ route('services.my') }}" class="flex items-center text-blue-500">
                    <i class="material-icons">arrow_back_ios</i>
                    <span>返回</span>
                </a>
            </div>
            <h1 class="text-xl font-semibold">我的服务</h1>
            <i class="material-icons text-gray-600">more_horiz</i>
        </header>

        <main class="p-4">
            <!-- 服务状态 -->
            <span class="inline-block {{ $service->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-sm px-2 py-1 rounded mb-4">
                {{ $service->is_active ? '发布中' : '已关闭' }}
            </span>

            <!-- 服务信息卡片 -->
            <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                <div class="flex items-center mb-2">
                    <i class="material-icons text-blue-500 mr-2 text-2xl">rocket_launch</i>
                    <h2 class="text-lg font-semibold text-gray-800">{{ $service->service_name }}</h2>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                        {{ $service->service_type }}
                    </span>
                </div>
                <div class="flex items-center">
                    <img alt="{{ $service->user->name }}" 
                         class="w-12 h-12 rounded-full mr-3" 
                         src="{{ $service->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($service->user->name) . '&background=random' }}"/>
                    <div>
                        <div class="flex items-center">
                            <p class="font-semibold text-gray-800">{{ $service->user->name }}老师</p>
                            <i class="material-icons text-blue-400 text-base mx-1">
                                {{ $service->user->gender === 'female' ? 'female' : 'male' }}
                            </i>
                            @if($service->user->is_verified_teacher)
                            <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-1 rounded-full">已认证</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ Str::limit($service->user->personal_introduction ?? '专业银行开户服务', 30) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- 服务详情 -->
            <div class="mt-6 text-gray-700 leading-relaxed">
                {!! nl2br(e($service->service_details)) !!}
            </div>

            <!-- 价格和统计 -->
            <div class="flex justify-between items-center mt-6">
                <div class="flex items-center">
                    <span class="text-yellow-500 text-2xl font-bold">¥ {{ number_format($service->service_amount, 0) }}</span>
                </div>
                <span class="text-sm text-gray-500">已帮助 {{ $service->completed_orders_count }} 人</span>
            </div>

            <!-- 温馨提示 -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">温馨提示：</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    香港开户一般流程为①线上申请、②预约面签、③线下面签、④领银行卡&成功激活。
                    <br/>
                    开户老师帮助完成线下面签即为服务完成，后续发卡激活由银行负责，老师无法干预，敬请理解。
                </p>
            </div>

            @if(session('success'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
        </main>

        <!-- 底部操作按钮 -->
        <footer class="fixed bottom-0 left-0 right-0 max-w-md mx-auto flex">
            <button onclick="confirmDelete()" class="w-1/3 py-4 bg-green-400 text-white text-lg font-semibold hover:bg-green-500 transition-colors">
                删除
            </button>
            <button onclick="window.location.href='{{ route('services.edit', $service) }}'" class="w-1/3 py-4 bg-blue-400 text-white text-lg font-semibold hover:bg-blue-500 transition-colors">
                编辑
            </button>
            <button onclick="toggleServiceStatus()" class="w-1/3 py-4 bg-blue-500 text-white text-lg font-semibold hover:bg-blue-600 transition-colors">
                {{ $service->is_active ? '关闭服务' : '开启服务' }}
            </button>
        </footer>

        <!-- Add bottom spacing for fixed footer -->
        <div class="h-20"></div>
    </div>

    <!-- 删除确认表单 -->
    <form id="deleteForm" action="{{ route('services.destroy', $service) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- 切换状态表单 -->
    <form id="toggleStatusForm" action="{{ route('services.toggle-status', $service) }}" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <script>
        function confirmDelete() {
            if (confirm('确定要删除这个服务吗？删除后无法恢复。')) {
                document.getElementById('deleteForm').submit();
            }
        }

        function toggleServiceStatus() {
            const action = {{ $service->is_active ? 'true' : 'false' }} ? '关闭' : '开启';
            if (confirm(`确定要${action}这个服务吗？`)) {
                document.getElementById('toggleStatusForm').submit();
            }
        }
    </script>
</body>
</html>