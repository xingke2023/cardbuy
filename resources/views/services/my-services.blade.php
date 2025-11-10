<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>我的服务</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-sm mx-auto bg-white">
        <header class="flex justify-between items-center p-4 border-b">
            <div class="flex items-center">
                <a href="{{ route('profile.show') }}" class="text-blue-500 mr-4">
                    <span class="material-icons">arrow_back_ios</span>
                </a>
                <span class="text-xl font-semibold">我的服务</span>
            </div>
            <span class="material-symbols-outlined text-blue-500">more_horiz</span>
        </header>

        <main class="p-4 space-y-4">
            @forelse($services as $service)
            <a href="{{ route('services.show', $service) }}" class="block">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start">
                            <span class="material-symbols-outlined text-blue-500 text-2xl mr-2">rocket_launch</span>
                            <h3 class="font-semibold text-gray-800">{{ $service->service_name }}</h3>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            @if($service->is_active) 
                                bg-green-100 text-green-600
                            @else 
                                bg-gray-200 text-gray-600
                            @endif
                        ">
                            {{ $service->is_active ? '发布中' : '关闭中' }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm mt-2">{{ Str::limit($service->service_details ?? '暂无服务介绍', 50) }}</p>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-red-500 font-bold">¥ {{ number_format($service->service_amount, 0) }}</span>
                        <span class="text-gray-400 text-sm">已帮助 {{ $service->completed_orders_count ?? 0 }} 人</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="text-center py-8 text-gray-500">
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">work_off</span>
                <p>暂无服务项目</p>
            </div>
            @endforelse

            <a href="{{ route('services.create') }}" class="w-full border-2 border-dashed border-blue-400 text-blue-500 font-semibold py-3 rounded-lg flex items-center justify-center hover:bg-blue-50 transition-colors">
                <span class="material-symbols-outlined mr-2">add</span>
                添加新服务
            </a>
        </main>

        <footer class="p-4 mt-6">
            {{-- <h2 class="text-lg font-bold border-l-4 border-blue-500 pl-2">我的服务</h2> --}}
        </footer>
    </div>
</body>
</html>