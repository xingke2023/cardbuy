<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>提交需求 - {{ $service->service_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f8fa;
            min-height: max(884px, 100dvh);
        }
        .icon-blue {
            color: #3b82f6;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 12px;
            transform: translateX(-50%);
            width: 100%;
            height: 2px;
            background-color: #e5e7eb;
            z-index: 1;
        }
        .timeline-item:last-child::after {
            width: 50%;
            left: 0;
        }
        .timeline-item:first-child::after {
            width: 50%;
            left: 50%;
        }
        .timeline-dot {
            position: relative;
            z-index: 2;
        }
        .active-dot {
            width: 12px;
            height: 12px;
            background-color: #3b82f6;
            border-radius: 50%;
        }
        .inactive-dot {
            width: 8px;
            height: 8px;
            background-color: #d1d5db;
            border-radius: 50%;
        }
        .timeline-text {
            font-size: 12px;
        }
        .active-text {
            color: #3b82f6;
        }
        .inactive-text {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-sm mx-auto bg-white">
        <!-- Header -->
        <header class="flex items-center justify-between p-4 bg-white border-b border-gray-200">
            <a class="text-blue-500 flex items-center" href="{{ route('teachers.show', $service->user) }}">
                <i class="material-icons">arrow_back_ios</i>
                <span class="text-lg">返回</span>
            </a>
            <h1 class="text-xl font-semibold">提交需求</h1>
            <button class="text-blue-500">
                <i class="material-icons">more_horiz</i>
            </button>
        </header>

        <main class="p-4">
            <!-- Service Information -->
            <div class="flex items-start space-x-2 mb-4">
                <i class="material-icons text-blue-500 mt-1">rocket_launch</i>
                <p class="text-gray-800 font-semibold">{{ $service->service_name }}</p>
            </div>

            <!-- Teacher Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <img alt="{{ $service->user->name }}" 
                         class="w-12 h-12 rounded-full mr-4" 
                         src="{{ $service->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($service->user->name) . '&background=random' }}"/>
                    <div>
                        <div class="flex items-center">
                            <h2 class="font-bold text-gray-800">{{ $service->user->name }}</h2>
                            <span class="material-icons text-blue-400 text-lg mx-1">{{ $service->user->gender === 'female' ? 'female' : 'male' }}</span>
                            @if($service->user->is_verified_teacher)
                            <span class="bg-yellow-100 text-yellow-600 text-xs font-semibold px-2 py-1 rounded-full">已认证</span>
                            @endif
                        </div>
                        <p class="text-gray-500 text-sm">{{ Str::limit($service->user->personal_introduction ?? '专业银行卡办理服务', 30) }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="flex justify-between items-start text-center mb-8">
                <div class="timeline-item flex-1">
                    <div class="flex justify-center mb-1">
                        <div class="timeline-dot active-dot"></div>
                    </div>
                    <p class="timeline-text active-text">提交需求并支付</p>
                </div>
                <div class="timeline-item flex-1">
                    <div class="flex justify-center mb-1">
                        <div class="timeline-dot inactive-dot"></div>
                    </div>
                    <p class="timeline-text inactive-text">获得老师联系方式</p>
                </div>
                <div class="timeline-item flex-1">
                    <div class="flex justify-center mb-1">
                        <div class="timeline-dot inactive-dot"></div>
                    </div>
                    <p class="timeline-text inactive-text">老师接受服务中</p>
                </div>
                <div class="timeline-item flex-1">
                    <div class="flex justify-center mb-1">
                        <div class="timeline-dot inactive-dot"></div>
                    </div>
                    <p class="timeline-text inactive-text">确认完成</p>
                </div>
                <div class="timeline-item flex-1">
                    <div class="flex justify-center mb-1">
                        <div class="timeline-dot inactive-dot"></div>
                    </div>
                    <p class="timeline-text inactive-text">评价</p>
                </div>
            </div>

            <!-- Order Form -->
            <form action="{{ route('orders.store', $service) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="text-gray-700 font-semibold mb-2 block" for="expected_completion_date">期望完成时间</label>
                    <div class="relative">
                        <input type="date" 
                               name="expected_completion_date" 
                               id="expected_completion_date"
                               class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800"
                               min="{{ date('Y-m-d') }}"
                               required>
                    </div>
                </div>

                <div>
                    <label class="text-gray-700 font-semibold mb-2 block" for="account_opening_area">期望开户区域</label>
                    <div class="relative">
                        <select name="account_opening_area" 
                                id="account_opening_area" 
                                class="w-full p-3 border border-gray-300 rounded-lg appearance-none bg-white text-gray-800"
                                required>
                            <option value="">请选择</option>
                            <option value="香港岛">香港岛</option>
                            <option value="九龙">九龙</option>
                            <option value="新界">新界</option>
                            <option value="全港区域">全港区域</option>
                            <option value="其他">其他</option>
                        </select>
                        <i class="material-icons absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">expand_more</i>
                    </div>
                </div>

                <div>
                    <label class="text-gray-700 font-semibold mb-2 block" for="share_contact_info">是否向老师公开您的联系方式?</label>
                    <div class="relative">
                        <select name="share_contact_info" 
                                id="share_contact_info" 
                                class="w-full p-3 border border-gray-300 rounded-lg appearance-none bg-white text-gray-800">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                        <i class="material-icons absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">expand_more</i>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <input name="contact_info" 
                           class="w-full p-3 bg-transparent placeholder-gray-400 focus:outline-none border-b border-gray-200" 
                           placeholder="请输入有效联系方式，方便老师与您沟通" 
                           type="text"/>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <label class="text-gray-700 font-semibold mb-2 block" for="other_requirements">其他需求</label>
                    <textarea name="other_requirements" 
                              class="w-full p-3 bg-transparent placeholder-gray-400 focus:outline-none border border-gray-300 rounded-lg" 
                              id="other_requirements" 
                              placeholder="请简要描述您的需求，便于老师了解您的情况。如, 目标银行、账户主要用途、其他特殊需求等。" 
                              rows="4"></textarea>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </main>

        <!-- Footer -->
        <footer class="fixed bottom-0 left-0 right-0 max-w-sm mx-auto bg-white">
            <div class="flex items-center justify-between p-4 border-t border-gray-200">
                <div class="flex items-center">
                    <span class="text-yellow-500 font-bold text-lg mr-1">¥ {{ number_format($service->service_amount, 0) }}</span>
                    <p class="text-gray-500 text-sm">平台担保交易, 开户不成功或老师未接受可全额退款。</p>
                </div>
            </div>
            <button type="submit" 
                    form="order-form"
                    onclick="document.querySelector('form').submit()"
                    class="w-full bg-blue-500 text-white font-bold py-4 hover:bg-blue-600 transition-colors">
                确认
            </button>
        </footer>

        <!-- Add bottom spacing for fixed footer -->
        <div class="h-32"></div>
    </div>
</body>
</html>