<div class="container mx-auto max-w-sm">
    <header class="bg-white flex items-center justify-between p-4">
        <a class="text-blue-500 flex items-center" href="{{ route('orders.teacher') }}">
            <i class="material-icons">arrow_back_ios</i>
            <span class="text-lg">返回</span>
        </a>
        <h1 class="text-xl font-semibold">服务订单</h1>
        <button class="text-blue-500">
            <i class="material-icons">more_horiz</i>
        </button>
    </header>

    <main class="p-4 space-y-4">
        <!-- 待处理提示 -->
        <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">用户已付款至平台，等待您接受</p>
            <p class="text-sm">处理不及时用户可能取消订单，请尽快处理</p>
        </div>

        <!-- 老师视角的流程进度 -->
        <div class="flex justify-between items-center text-sm text-gray-500">
            <div class="flex flex-col items-center text-blue-500">
                <span>用户</span>
                <span>已支付</span>
            </div>
            <i class="material-icons text-gray-300">chevron_right</i>
            <div class="flex flex-col items-center text-blue-500">
                <span>等待</span>
                <span>接受</span>
            </div>
            <i class="material-icons text-gray-300">chevron_right</i>
            <div class="flex flex-col items-center">
                <span>服务中</span>
            </div>
            <i class="material-icons text-gray-300">chevron_right</i>
            <div class="flex flex-col items-center">
                <span>确认</span>
                <span>完成</span>
            </div>
            <i class="material-icons text-gray-300">chevron_right</i>
            <div class="flex flex-col items-center">
                <span>评价</span>
            </div>
        </div>

        <!-- 服务和用户信息 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center mb-4">
                <i class="material-icons text-blue-500 mr-2">rocket_launch</i>
                <p class="font-semibold">{{ $order->service ? $order->service->service_name : '服务已删除' }}</p>
            </div>
            
            <div class="bg-gray-100 p-3 rounded-lg flex items-center">
                <img alt="{{ $order->user->name }}" 
                     class="w-10 h-10 rounded-full mr-3" 
                     src="{{ $order->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name) . '&background=random' }}"/>
                <div>
                    <div class="flex items-center">
                        <p class="font-semibold mr-2">{{ $order->user->name }}</p>
                        <span class="text-blue-500 text-sm">
                            {{ $order->user->gender === 'female' ? '♀' : '♂' }}
                        </span>
                        @if($order->user->is_verified_teacher)
                        <span class="ml-2 bg-blue-100 text-blue-500 text-xs px-2 py-1 rounded-full">已认证</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">订单用户</p>
                </div>
            </div>

            @if($order->share_contact_info)
            <div class="mt-4 space-y-2 text-gray-700">
                <p class="font-semibold">用户联系方式</p>
                @if($order->user->phone)
                <p>手机: <span class="text-blue-500">{{ $order->user->phone }}</span></p>
                @endif
                @if($order->user->wechat)
                <p>微信: <span class="text-blue-500">{{ $order->user->wechat }}</span></p>
                @endif
                @if($order->user->whatsapp)
                <p>WhatsApp: <span class="text-blue-500">{{ $order->user->whatsapp }}</span></p>
                @endif
                @if(!$order->user->phone && !$order->user->wechat && !$order->user->whatsapp)
                <p class="text-gray-500 text-sm">用户暂未提供联系方式</p>
                @endif
            </div>
            @else
            <div class="mt-4 text-gray-500 text-sm">
                <p>用户选择不公开联系方式</p>
            </div>
            @endif
        </div>

        <!-- 订单信息 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">订单信息</h2>
            
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <span class="text-gray-500">期望完成时间</span>
                <span>{{ $order->expected_completion_date ? $order->expected_completion_date->format('Y-m-d') : '未指定' }}</span>
            </div>
            
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <span class="text-gray-500">期望开户区域</span>
                <span>{{ $order->account_opening_area ?: '未指定' }}</span>
            </div>
            
            @if($order->other_requirements)
            <div class="mb-4 pb-4 border-b">
                <p class="text-gray-500 mb-2">其他需求</p>
                <p class="text-gray-700">{{ $order->other_requirements }}</p>
            </div>
            @endif
            
            <div class="space-y-2 text-sm text-gray-500">
                <div class="flex justify-between">
                    <span>订单编号</span>
                    <span>#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span>创建时间</span>
                    <span>{{ $order->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>支付金额</span>
                    <span class="text-red-500 font-semibold">¥ {{ number_format($order->payment_amount, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- 如果订单已经被处理，显示处理信息 -->
        @if($order->status === 'accepted' && ($order->teacher_recommended_bank || $order->estimated_online_appointment_time || $order->teacher_message))
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4 text-green-600">您的回复信息</h2>
            
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
                <span class="text-gray-500">留言内容</span>
                <p class="text-gray-800 mt-1">{{ $order->teacher_message }}</p>
            </div>
            @endif
            
            <div class="text-right text-sm text-gray-400">
                回复时间：{{ $order->accepted_at ? $order->accepted_at->format('Y-m-d H:i:s') : '未知' }}
            </div>
        </div>
        @endif

        <!-- 如果订单被拒绝，显示拒绝信息 -->
        @if($order->status === 'rejected' && $order->rejection_reason)
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4 text-red-600">拒绝原因</h2>
            <p class="text-gray-800">{{ $order->rejection_reason }}</p>
            <div class="text-right text-sm text-gray-400 mt-3">
                拒绝时间：{{ $order->rejected_at ? $order->rejected_at->format('Y-m-d H:i:s') : '未知' }}
            </div>
        </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
    </main>

    <!-- 底部操作按钮 -->
    <footer class="fixed bottom-0 left-0 right-0 max-w-sm mx-auto flex">
        <button onclick="openRejectModal()" class="w-1/2 bg-gray-200 text-gray-700 py-4 text-lg hover:bg-gray-300 transition-colors">
            拒绝
        </button>
        <button onclick="openAcceptModal()" class="w-1/2 bg-blue-500 text-white py-4 text-lg hover:bg-blue-600 transition-colors">
            接受
        </button>
    </footer>

    <!-- Add bottom spacing for fixed footer -->
    <div class="h-20"></div>
</div>

<!-- 接受订单模态框 -->
<div id="acceptModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">接受订单</h3>
        
        <form action="{{ route('orders.accept', $order) }}" method="POST" id="acceptForm">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">老师推荐银行</label>
                <input type="text" name="teacher_recommended_bank" 
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="请输入推荐银行">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">预计线上预约时间</label>
                <input type="datetime-local" name="estimated_online_appointment_time" 
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">注意事项特别说明</label>
                <textarea name="teacher_message" rows="4" 
                          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 resize-none" 
                          placeholder="请输入注意事项或特别说明"></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeAcceptModal()" 
                        class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">
                    取消
                </button>
                <button type="submit" 
                        class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                    确认接受
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 拒绝订单模态框 -->
<div id="rejectModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">拒绝订单</h3>
        
        <form action="{{ route('orders.reject', $order) }}" method="POST" id="rejectForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">拒绝理由</label>
                <textarea name="rejection_reason" rows="4" required
                          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 resize-none" 
                          placeholder="请输入拒绝理由"></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeRejectModal()" 
                        class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">
                    取消
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-colors">
                    确认拒绝
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAcceptModal() {
        document.getElementById('acceptModal').classList.add('active');
    }

    function closeAcceptModal() {
        document.getElementById('acceptModal').classList.remove('active');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.add('active');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
    }

    // Close modals when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
</script>