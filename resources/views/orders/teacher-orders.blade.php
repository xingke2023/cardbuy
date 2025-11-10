<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>我服务的订单</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8fafc;
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body>
    <div class="bg-white">
        <header class="flex justify-between items-center p-4 border-b">
            <a href="{{ route('profile.show') }}" class="material-icons text-blue-500">arrow_back_ios</a>
            <h1 class="text-xl font-semibold">我服务的订单</h1>
            <span class="material-icons text-gray-400">more_horiz</span>
        </header>

        <main class="p-4 space-y-4">
            @forelse($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="block">
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <div class="px-4 py-2">
                    @php
                        $statusConfig = [
                            'pending' => ['class' => 'text-yellow-600 bg-yellow-100', 'label' => '待接受'],
                            'rejected' => ['class' => 'text-red-600 bg-red-100', 'label' => '已拒绝'],
                            'accepted' => ['class' => 'text-green-600 bg-green-100', 'label' => '进行中'],
                            'cancelling' => ['class' => 'text-purple-600 bg-purple-100', 'label' => '取消中'],
                            'cancelled' => ['class' => 'text-gray-600 bg-gray-100', 'label' => '已取消'],
                            'completing' => ['class' => 'text-blue-600 bg-blue-100', 'label' => '完成确认中'],
                            'completed' => ['class' => 'text-blue-600 bg-blue-100', 'label' => '已完成,等待用户确认'],
                            'confirmed' => ['class' => 'text-green-600 bg-green-100', 'label' => '已确认完成'],
                        ];
                        $config = $statusConfig[$order->status] ?? ['class' => 'text-gray-600 bg-gray-100', 'label' => $order->status];
                    @endphp
                    
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $config['class'] }}">
                            {{ $config['label'] }}
                        </span>
                        @if(in_array($order->status, ['cancelling', 'completing']))
                        <span class="text-xs text-yellow-500">
                            等待用户确认{{ $order->status === 'cancelling' ? '取消' : '完成' }} 23:21:22
                        </span>
                        @endif
                    </div>
                </div>

                <div class="p-4 border-t">
                    <div class="flex items-center space-x-3">
                        <span class="material-icons text-blue-500 text-3xl">rocket_launch</span>
                        <p class="text-gray-800 font-medium">
                            {{ $order->service ? $order->service->service_name : '服务已删除' }}
                        </p>
                    </div>
                    <div class="flex items-center mt-2">
                        <span class="material-icons text-yellow-500">monetization_on</span>
                        <span class="text-yellow-600 font-bold text-lg ml-1">{{ number_format($order->payment_amount, 0) }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center px-4 py-3 border-t text-sm text-gray-500">
                    <div class="flex items-center">
                        <span>发起用户：</span>
                        <img alt="User avatar" 
                             class="w-6 h-6 rounded-full mx-2" 
                             src="{{ $order->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name) . '&background=random' }}"/>
                        <span>{{ $order->user->name }}</span>
                    </div>
                    <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
                </div>
            </a>
            @empty
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                <span class="material-icons text-6xl text-gray-300 mb-4">assignment</span>
                <p>暂无服务订单</p>
            </div>
            @endforelse
        </main>
    </div>
</body>
</html>