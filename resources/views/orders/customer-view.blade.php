<div class="max-w-sm mx-auto bg-white">
    <header class="flex items-center justify-between p-4 bg-white border-b">
        <div class="flex items-center">
            <a href="{{ Auth::user()->user_type === 'teacher' ? route('orders.teacher') : route('profile.show') }}" class="flex items-center text-blue-500">
                <i class="material-icons">arrow_back_ios</i>
                <span>返回</span>
            </a>
        </div>
        <h1 class="text-xl font-semibold">服务订单</h1>
        <button class="text-blue-500">
            <i class="material-icons">more_horiz</i>
        </button>
    </header>

    <main class="p-4">
        <!-- 订单状态提示 -->
        <div class="p-3 rounded-lg flex items-center mb-4
            @switch($currentStatus['color'])
                @case('yellow')
                    bg-yellow-100 text-yellow-800
                    @break
                @case('blue')
                    bg-blue-100 text-blue-800
                    @break
                @case('green')
                    bg-green-100 text-green-800
                    @break
                @case('red')
                    bg-red-100 text-red-800
                    @break
                @default
                    bg-gray-100 text-gray-800
            @endswitch
        ">
            <span class="material-icons mr-2">
                @switch($order->status)
                    @case('pending')
                        hourglass_empty
                        @break
                    @case('accepted')
                        check_circle
                        @break
                    @case('paid')
                        payment
                        @break
                    @case('in_progress')
                        work
                        @break
                    @case('completed')
                        task_alt
                        @break
                    @case('cancelled')
                    @case('rejected')
                        cancel
                        @break
                    @default
                        info
                @endswitch
            </span>
            {{ $currentStatus['label'] }}
        </div>

        <!-- 订单流程进度 -->
        <div class="flex justify-between items-center text-xs text-gray-500 mb-6">
            <div class="flex flex-col items-center {{ $currentStatus['step'] >= 1 ? 'text-blue-500' : '' }}">
                <span>提交需求并支付</span>
            </div>
            <span class="material-icons text-gray-300">chevron_right</span>
            <div class="flex flex-col items-center {{ $currentStatus['step'] >= 2 ? 'text-blue-500' : '' }}">
                <span>获得老师联系方式</span>
            </div>
            <span class="material-icons text-gray-300">chevron_right</span>
            <div class="flex flex-col items-center {{ $currentStatus['step'] >= 3 ? 'text-blue-500' : '' }}">
                <span>老师接受服务中</span>
            </div>
            <span class="material-icons text-gray-300">chevron_right</span>
            <div class="flex flex-col items-center {{ $currentStatus['step'] >= 4 ? 'text-blue-500' : '' }}">
                <span>确认完成</span>
            </div>
            <span class="material-icons text-gray-300">chevron_right</span>
            <div class="flex flex-col items-center {{ $currentStatus['step'] >= 5 ? 'text-blue-500' : '' }}">
                <span>评价</span>
            </div>
        </div>

        <!-- 服务和老师信息 -->
        <div class="bg-white rounded-lg p-4 mb-4">
            <div class="flex items-center mb-4">
                <span class="material-icons text-blue-500 mr-2">rocket_launch</span>
                <p class="font-semibold">{{ $order->service ? $order->service->service_name : '服务已删除' }}</p>
            </div>
            
            <div class="flex items-start">
                <img alt="{{ $order->teacher->name }}" 
                     class="w-12 h-12 rounded-full mr-4" 
                     src="{{ $order->teacher->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->teacher->name) . '&background=random' }}"/>
                <div class="flex-1">
                    <div class="flex items-center">
                        <h3 class="font-semibold">{{ $order->teacher->name }}</h3>
                        <span class="material-icons text-blue-400 text-base mx-1">
                            {{ $order->teacher->gender === 'female' ? 'female' : 'male' }}
                        </span>
                        @if($order->teacher->is_verified_teacher)
                        <span class="bg-blue-100 text-blue-600 text-xs px-2 py-0.5 rounded-full">已认证</span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-sm">{{ Str::limit($order->teacher->personal_introduction ?? '专业银行开户服务', 40) }}</p>
                </div>
            </div>

            @if(in_array($order->status, ['accepted', 'paid', 'in_progress', 'completed']) && $order->share_contact_info)
            <div class="mt-4 pt-4 border-t">
                <h4 class="font-semibold mb-2">联系方式</h4>
                @if($order->teacher->phone)
                <p class="text-gray-700">手机: <span class="text-black">{{ $order->teacher->phone }}</span></p>
                @endif
                @if($order->teacher->wechat)
                <p class="text-gray-700">微信: <span class="text-black">{{ $order->teacher->wechat }}</span></p>
                @endif
                @if($order->teacher->whatsapp)
                <p class="text-gray-700">WhatsApp: <span class="text-black">{{ $order->teacher->whatsapp }}</span></p>
                @endif
                @if(!$order->teacher->phone && !$order->teacher->wechat && !$order->teacher->whatsapp)
                <p class="text-gray-500 text-sm">老师暂未提供联系方式</p>
                @endif
            </div>
            @endif
        </div>

        <!-- 订单信息 -->
        <div class="bg-white rounded-lg p-4 mb-4">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">订单信息</h3>
            
            <div class="flex justify-between items-center mb-3">
                <span class="text-gray-500">期望完成时间</span>
                <span class="font-semibold">{{ $order->expected_completion_date ? $order->expected_completion_date->format('Y-m-d') : '未指定' }}</span>
            </div>
            
            <div class="flex justify-between items-center mb-3">
                <span class="text-gray-500">期望开户区域</span>
                <span class="font-semibold">{{ $order->account_opening_area ?: '未指定' }}</span>
            </div>
            
            @if($order->other_requirements)
            <div class="mb-4">
                <span class="text-gray-500">其他需求</span>
                <p class="text-gray-800 mt-1">{{ $order->other_requirements }}</p>
            </div>
            @endif
            
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-2 text-sm">
                    <span class="text-gray-500">订单编号</span>
                    <span class="text-gray-800">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center mb-2 text-sm">
                    <span class="text-gray-500">创建时间</span>
                    <span class="text-gray-800">{{ $order->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">支付金额</span>
                    <span class="text-red-500 font-semibold">¥ {{ number_format($order->payment_amount, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- 老师回复信息 -->
        @if($order->status === 'accepted' && ($order->teacher_recommended_bank || $order->estimated_online_appointment_time || $order->teacher_message))
        <div class="bg-white rounded-lg p-4 mb-4">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2 text-green-600">老师回复</h3>
            
            @if($order->teacher_recommended_bank)
            <div class="mb-3">
                <span class="text-gray-500">推荐银行</span>
                <p class="text-gray-800 mt-1">{{ $order->teacher_recommended_bank }}</p>
            </div>
            @endif
            
            @if($order->estimated_online_appointment_time)
            <div class="mb-3">
                <span class="text-gray-500">预计线上预约时间</span>
                <p class="text-gray-800 mt-1">{{ \Carbon\Carbon::parse($order->estimated_online_appointment_time)->format('Y-m-d H:i') }}</p>
            </div>
            @endif
            
            @if($order->teacher_message)
            <div class="mb-3">
                <span class="text-gray-500">老师留言</span>
                <p class="text-gray-800 mt-1">{{ $order->teacher_message }}</p>
            </div>
            @endif
            
            <div class="text-right text-sm text-gray-400">
                回复时间：{{ $order->accepted_at ? $order->accepted_at->format('Y-m-d H:i:s') : '未知' }}
            </div>
        </div>
        @endif

        <!-- 拒绝原因 -->
        @if($order->status === 'rejected' && $order->rejection_reason)
        <div class="bg-white rounded-lg p-4 mb-4">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2 text-red-600">拒绝原因</h3>
            <p class="text-gray-800">{{ $order->rejection_reason }}</p>
            <div class="text-right text-sm text-gray-400 mt-3">
                拒绝时间：{{ $order->rejected_at ? $order->rejected_at->format('Y-m-d H:i:s') : '未知' }}
            </div>
        </div>
        @endif

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
    </main>

    <!-- 底部操作按钮 -->
    @if(Auth::id() === $order->user_id)
    <footer class="fixed bottom-0 left-0 right-0 max-w-sm mx-auto flex">
        @if(in_array($order->status, ['pending', 'accepted']))
        <button class="w-1/2 bg-gray-200 text-gray-800 py-4 text-center hover:bg-gray-300 transition-colors" 
                onclick="cancelOrder()">
            取消订单
        </button>
        @endif
        
        @if($order->status === 'completed')
        <button class="w-full bg-green-500 text-white py-4 text-center hover:bg-green-600 transition-colors" 
                onclick="openConfirmModal()">
            确认完成
        </button>
        @elseif(in_array($order->status, ['accepted', 'paid', 'in_progress']) && $order->share_contact_info)
        <button class="w-1/2 bg-blue-500 text-white py-4 text-center hover:bg-blue-600 transition-colors" 
                onclick="contactTeacher()">
            联系老师
        </button>
        @elseif($order->status === 'pending')
        <button class="w-1/2 bg-blue-500 text-white py-4 text-center hover:bg-blue-600 transition-colors" 
                onclick="showWaitingMessage()">
            联系老师
        </button>
        @endif
    </footer>

    <!-- Add bottom spacing for fixed footer -->
    <div class="h-20"></div>
    @endif
</div>

<!-- 确认完成模态框 -->
<div id="confirmModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4 text-green-600">确认完成订单</h3>
        
        <div class="mb-6">
            <p class="text-gray-700 mb-4">确认老师已经帮助您完成了银行开户服务？</p>
            <p class="text-sm text-gray-500">确认后，款项将支付给老师，此操作不可撤销。</p>
        </div>
        
        <form action="{{ route('orders.confirm', $order) }}" method="POST" id="confirmForm">
            @csrf
            @method('PATCH')
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeConfirmModal()" 
                        class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">
                    取消
                </button>
                <button type="submit" 
                        class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    确认完成
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function cancelOrder() {
        if (confirm('确定要取消这个订单吗？')) {
            // 这里可以添加取消订单的逻辑
            alert('取消订单功能待实现');
        }
    }

    function contactTeacher() {
        @if($order->teacher->wechat)
            alert('请添加老师微信：{{ $order->teacher->wechat }}');
        @elseif($order->teacher->phone)
            alert('请联系老师电话：{{ $order->teacher->phone }}');
        @elseif($order->teacher->whatsapp)
            alert('请联系老师WhatsApp：{{ $order->teacher->whatsapp }}');
        @else
            alert('老师暂未提供联系方式，请稍后再试');
        @endif
    }

    function showWaitingMessage() {
        alert('请等待老师接受订单后，即可获得联系方式');
    }

    function openConfirmModal() {
        document.getElementById('confirmModal').style.display = 'flex';
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('confirmModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('confirmModal')) {
            closeConfirmModal();
        }
    });
</script>