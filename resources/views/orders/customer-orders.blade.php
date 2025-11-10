<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>我发起的订单</title>
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
    <div class="max-w-md mx-auto bg-white min-h-screen">
        <header class="bg-white p-4 flex justify-between items-center shadow-sm sticky top-0 z-10">
            <a href="{{ route('profile.show') }}" class="flex items-center text-blue-500">
                <i class="material-icons">arrow_back_ios</i>
                <span>返回</span>
            </a>
            <h1 class="text-lg font-semibold">我发起的订单</h1>
            <button class="text-gray-600">
                <span class="material-icons">more_horiz</span>
            </button>
        </header>

        <main class="p-4 space-y-4">
            @forelse($orders as $order)
            <div class="bg-white rounded-lg shadow-md p-4" onclick="window.location.href='{{ route('orders.show', $order) }}'">
                <div class="flex items-center justify-between mb-3">
                    @php
                        $statusStyles = [
                            'pending' => ['text' => '待接受', 'class' => 'text-blue-500 bg-blue-100'],
                            'accepted' => ['text' => '已接受', 'class' => 'text-green-500 bg-green-100'],
                            'paid' => ['text' => '已支付', 'class' => 'text-green-500 bg-green-100'],
                            'in_progress' => ['text' => '进行中', 'class' => 'text-green-500 bg-green-100'],
                            'completed' => ['text' => '待确认', 'class' => 'text-yellow-600 bg-yellow-100'],
                            'confirmed' => ['text' => '完成已确认', 'class' => 'text-green-500 bg-green-100'],
                            'cancelled' => ['text' => '已取消', 'class' => 'text-gray-500 bg-gray-200'],
                            'rejected' => ['text' => '已拒绝', 'class' => 'text-red-500 bg-red-100'],
                            'cancelling' => ['text' => '取消中', 'class' => 'text-yellow-600 bg-yellow-100'],
                            'confirming_completion' => ['text' => '完成确认中', 'class' => 'text-yellow-600 bg-yellow-100'],
                        ];
                        $currentStatus = $statusStyles[$order->status] ?? ['text' => '未知状态', 'class' => 'text-gray-500 bg-gray-200'];
                    @endphp
                    
                    <span class="text-xs font-semibold {{ $currentStatus['class'] }} px-2 py-1 rounded-full">
                        {{ $currentStatus['text'] }}
                    </span>
                    
                    @if(in_array($order->status, ['cancelling', 'confirming_completion']))
                    <span class="text-sm text-red-500">
                        @if($order->status === 'cancelling')
                            等待老师确认取消 <span class="font-semibold">23:21:22</span>
                        @else
                            等待用户确认完成 <span class="font-semibold">23:21:22</span>
                        @endif
                    </span>
                    @endif
                </div>
                
                <div class="flex items-start space-x-3 mb-4">
                    <span class="material-icons text-blue-500 mt-1">rocket_launch</span>
                    <p class="font-semibold text-gray-800">
                        {{ $order->service ? $order->service->service_name : '服务已删除' }}
                    </p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded-lg flex items-center space-x-3 mb-4">
                    <img alt="{{ $order->teacher->name }}" 
                         class="w-10 h-10 rounded-full" 
                         src="{{ $order->teacher->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->teacher->name) . '&background=random' }}"/>
                    <div>
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-900">{{ $order->teacher->name }}老师</span>
                            <span class="material-icons text-blue-500 text-sm ml-1">
                                {{ $order->teacher->gender === 'female' ? 'female' : 'male' }}
                            </span>
                            @if($order->teacher->is_verified_teacher)
                            <span class="ml-2 text-xs font-semibold text-yellow-500 bg-yellow-100 px-2 py-0.5 rounded-full">已认证</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ Str::limit($order->teacher->personal_introduction ?? '专业银行开户服务', 30) }}
                        </p>
                    </div>
                </div>
                
                <div class="text-right text-sm text-gray-400">
                    <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <i class="material-icons text-gray-400 text-6xl mb-4">inbox</i>
                <p class="text-gray-500 text-lg">暂无订单</p>
                <p class="text-gray-400 text-sm">去找老师下单吧</p>
                <a href="{{ route('teachers.index') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    浏览老师
                </a>
            </div>
            @endforelse
        </main>
    </div>
</body>
</html>