<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $teacher->name }} - 价值派·开户易</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            background-color: #f8f8f8;
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-lg bg-white">
        <!-- Header -->
        <header class="bg-white p-4 flex justify-between items-center">
            <a class="flex items-center text-blue-500" href="{{ route('teachers.index') }}">
                <span class="material-icons">arrow_back_ios</span>
                <span>返回</span>
            </a>
            <h1 class="text-lg font-semibold">价值派·开户易</h1>
            <button class="material-icons text-gray-600">more_horiz</button>
        </header>

        <main class="p-4">
            <!-- Teacher Profile Section -->
            <section class="flex items-center mb-6">
                <img alt="{{ $teacher->name }}" 
                     class="w-20 h-20 rounded-full mr-4" 
                     src="{{ $teacher->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&background=random' }}"/>
                <div>
                    <div class="flex items-center mb-1">
                        <h2 class="text-lg font-semibold mr-2">{{ $teacher->name }}</h2>
                        @if($teacher->is_verified_teacher)
                        <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-1 rounded-full flex items-center">
                            <span class="material-icons text-xs mr-1">check_circle</span>已认证
                        </span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-sm">{{ $teacher->personal_introduction ?? '专业银行卡办理服务' }}</p>
                    
                    @if($teacher->specialties)
                    <div class="mt-2 space-x-2">
                        @foreach(explode(',', $teacher->specialties) as $specialty)
                        <span class="bg-blue-100 text-blue-500 text-xs px-2 py-1 rounded">{{ trim($specialty) }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    <p class="text-gray-500 text-sm mt-2">
                        已帮助 <span class="text-orange-500">{{ $teacher->completed_orders }}</span> 人 
                        · 评分 <span class="text-orange-500">{{ number_format($teacher->current_rating, 1) }}</span>
                    </p>
                </div>
            </section>

            <!-- Services Section -->
            <section class="mb-6">
                <div class="border-l-4 border-blue-500 pl-3 mb-4">
                    <h3 class="text-lg font-bold">我的服务</h3>
                </div>
                
                @forelse($teacher->services as $service)
                <a href="{{ route('orders.create', $service) }}" class="block bg-white rounded-lg p-4 mb-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start">
                        <span class="material-icons text-blue-500 mr-3 mt-1">rocket_launch</span>
                        <div class="flex-1">
                            <h4 class="font-semibold mb-2">{{ $service->service_name }}</h4>
                            <p class="text-gray-500 text-sm mb-2">{{ Str::limit($service->service_details, 100) }}</p>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-orange-500 font-bold text-lg">💰 {{ number_format($service->service_amount, 0) }}</span>
                                <span class="text-gray-400 text-sm">已帮助 {{ $service->completed_orders_count }} 人</span>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="bg-white rounded-lg p-4 text-center text-gray-500">
                    <span class="material-icons text-4xl mb-2">work_off</span>
                    <p>暂无服务项目</p>
                </div>
                @endforelse
            </section>

            <!-- Personal Introduction Section -->
            <section class="mb-6">
                <div class="border-l-4 border-blue-500 pl-3 mb-4">
                    <h3 class="text-lg font-bold">个人介绍</h3>
                </div>
                <div class="text-gray-600 text-sm leading-relaxed">
                    @if($teacher->personal_introduction)
                        {!! nl2br(e($teacher->personal_introduction)) !!}
                    @else
                        <p>该老师还未填写详细介绍。</p>
                    @endif
                    
                    @if($teacher->serviceable_banks)
                    <p class="mt-3">
                        <span class="font-semibold">【可服务银行】</span>{{ $teacher->serviceable_banks }}
                    </p>
                    @endif
                    
                    @if($teacher->organization)
                    <p class="mt-2">
                        <span class="font-semibold">【任职机构】</span>{{ $teacher->organization }}
                    </p>
                    @endif
                    
                    @if($teacher->city)
                    <p class="mt-2">
                        <span class="font-semibold">【常驻城市】</span>{{ $teacher->city }}
                    </p>
                    @endif
                </div>
            </section>

            <!-- Reviews Section -->
            <section>
                <div class="border-l-4 border-blue-500 pl-3 mb-4">
                    <h3 class="text-lg font-bold">用户评价</h3>
                </div>
                
                @forelse($teacher->teacherReviews->take(5) as $review)
                <div class="bg-white rounded-lg p-4 mb-4">
                    <div class="flex items-center mb-2">
                        <span class="material-icons text-gray-500 mr-2">account_circle</span>
                        <span class="font-semibold">用户 {{ $review->user->name ?? '匿名' }}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-2">{{ $review->comment ?? '用户未留下评价内容' }}</p>
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>评分 {{ $review->rating }}.0</span>
                        <span>{{ $review->created_at->format('Y-m-d') }}</span>
                    </div>
                    @if($review->order)
                    <div class="mt-2 text-sm text-gray-500 flex items-center bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons text-blue-500 mr-2 text-base">work_outline</span>
                        <span>订单服务相关</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="bg-white rounded-lg p-4 text-center text-gray-500">
                    <span class="material-icons text-4xl mb-2">rate_review</span>
                    <p>暂无用户评价</p>
                </div>
                @endforelse
            </section>
        </main>

        <!-- Footer Actions -->
        <footer class="fixed bottom-0 left-0 right-0 max-w-lg mx-auto bg-white border-t flex">
            <button class="flex-1 flex items-center justify-center p-4 text-green-500 bg-teal-100">
                <span class="material-icons mr-1">favorite</span>
                <span>收藏</span>
            </button>
            <button class="flex-1 flex items-center justify-center p-4 text-green-500 bg-teal-100">
                <span class="material-icons mr-1">headset_mic</span>
                <span>客服</span>
            </button>
            <button class="flex-1 p-4 bg-blue-500 text-white font-semibold">
                一键约聊
            </button>
        </footer>
        
        <!-- Bottom spacing for fixed footer -->
        <div class="h-20"></div>
    </div>
</body>
</html>