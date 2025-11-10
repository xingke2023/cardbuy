<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>申请开户老师</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
            min-height: max(884px, 100dvh);
        }
        .header {
            background-color: #f8f9fa;
        }
        .btn-add {
            color: #1e88e5;
            border-color: #1e88e5;
        }
        .tag {
            background-color: #f1f3f4;
        }
        .material-icons {
            vertical-align: middle;
        }
        .modal {
            display: none;
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white">
        <header class="header flex items-center justify-between p-4 border-b border-gray-200">
            <a class="flex items-center text-blue-500" href="{{ route('profile.show') }}">
                <i class="material-icons">arrow_back_ios</i>
                <span class="text-lg">返回</span>
            </a>
            <h1 class="text-xl font-semibold">申请开户老师</h1>
            <button class="text-blue-500">
                <i class="material-icons">more_horiz</i>
            </button>
        </header>

        <form action="{{ route('teacher.submit') }}" method="POST" id="teacherApplicationForm">
            @csrf
            
            <main class="p-4">
                <!-- 基本资料 -->
                <div class="mb-4">
                    <h2 class="text-lg font-semibold mb-2">基本资料</h2>
                    <div class="flex items-center justify-between p-2 rounded-lg">
                        <div class="flex items-center">
                            <img alt="User avatar" 
                                 class="w-12 h-12 rounded-full mr-4" 
                                 src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"/>
                            <span class="text-lg">{{ $user->name }}</span>
                        </div>
                        <a class="flex items-center text-gray-500" href="{{ route('profile.edit') }}">
                            <span>编辑</span>
                            <i class="material-icons">arrow_forward_ios</i>
                        </a>
                    </div>
                </div>

                <!-- 个人介绍 -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">个人介绍</h2>
                    <div class="p-3 border border-gray-200 rounded-lg cursor-pointer" onclick="openIntroductionModal()">
                        <p class="text-gray-500 text-sm" id="introductionText">
                            {{ $user->personal_introduction ?: '请详细介绍一下自己，以便用户更全面地了解您，如：个人资历、过往开户服务案例、优势开户资源、特殊开户渠道、能为用户够提供的特色服务等。' }}
                        </p>
                    </div>
                    <input type="hidden" name="personal_introduction" value="{{ $user->personal_introduction }}" id="hiddenIntroduction">
                </div>

                <!-- 擅长银行 -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold">擅长银行</h2>
                        <button type="button" class="btn-add border-2 rounded-full px-3 py-1 text-sm flex items-center" onclick="openBankModal()">
                            <i class="material-icons text-sm mr-1">add</i>
                            <span>添加新银行</span>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2" id="banksContainer">
                        @foreach($banks as $bank)
                        <span class="tag text-gray-700 px-3 py-1 rounded-full flex items-center">
                            {{ $bank }}
                            <i class="material-icons text-sm ml-1 cursor-pointer" onclick="removeBank('{{ $bank }}')">close</i>
                        </span>
                        @endforeach
                    </div>
                    <input type="hidden" name="banks" value="{{ json_encode($banks) }}" id="hiddenBanks">
                </div>

                <!-- 添加标签 -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold">添加标签 <span class="text-gray-400 font-normal text-sm">如您擅长的领域</span></h2>
                        <button type="button" class="btn-add border-2 rounded-full px-3 py-1 text-sm flex items-center" onclick="openTagModal()">
                            <i class="material-icons text-sm mr-1">add</i>
                            <span>添加新标签</span>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2" id="tagsContainer">
                        @foreach($tags as $tag)
                        <span class="tag text-gray-700 px-3 py-1 rounded-full flex items-center">
                            {{ $tag }}
                            <i class="material-icons text-sm ml-1 cursor-pointer" onclick="removeTag('{{ $tag }}')">close</i>
                        </span>
                        @endforeach
                    </div>
                    <input type="hidden" name="tags" value="{{ json_encode($tags) }}" id="hiddenTags">
                </div>

                <!-- 设置服务 -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold">设置服务 <span class="text-gray-400 font-normal text-sm">请添加至少一个服务</span></h2>
                        <button type="button" class="btn-add border-2 rounded-full px-3 py-1 text-sm flex items-center" onclick="openServiceModal()">
                            <i class="material-icons text-sm mr-1">add</i>
                            <span>添加新服务</span>
                        </button>
                    </div>
                    <input type="hidden" name="services" value="{{ json_encode($services->toArray()) }}" id="hiddenServices">
                </div>

                @foreach($services as $service)
                <div class="p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-base mb-2">
                                <i class="material-icons text-blue-500 align-bottom">rocket_launch</i>
                                {{ $service['service_name'] }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-4">
                                {{ $service['service_details'] }}
                            </p>
                            <div class="flex items-center text-sm">
                                <span class="flex items-center text-yellow-500 mr-4">
                                    <i class="material-icons text-base">monetization_on</i>
                                    <span class="font-bold">{{ $service['service_amount'] }}</span>
                                </span>
                                <span class="text-gray-500">已帮助 {{ $service['completed_orders_count'] }} 人</span>
                            </div>
                        </div>
                        <span class="bg-yellow-100 text-yellow-600 text-xs font-semibold px-2 py-1 rounded">审核中</span>
                    </div>
                </div>
                @endforeach

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </main>
        </form>

        <footer class="fixed bottom-0 w-full max-w-md mx-auto">
            <button type="submit" form="teacherApplicationForm" class="w-full bg-blue-500 text-white py-4 text-lg font-semibold hover:bg-blue-600 transition-colors">
                提交审核
            </button>
        </footer>

        <!-- Add bottom spacing for fixed footer -->
        <div class="h-20"></div>
    </div>

    <!-- Introduction Modal -->
    <div id="introductionModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">编辑个人介绍</h3>
            <textarea id="introductionInput" rows="6" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 resize-none" placeholder="请详细介绍一下自己..."></textarea>
            <div class="flex space-x-3 mt-4">
                <button id="introductionCancel" class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">取消</button>
                <button id="introductionSave" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg">确定</button>
            </div>
        </div>
    </div>

    <!-- Bank Modal -->
    <div id="bankModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">添加银行</h3>
            <input type="text" id="bankInput" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="请输入银行名称">
            <div class="flex space-x-3 mt-4">
                <button id="bankCancel" class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">取消</button>
                <button id="bankSave" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg">添加</button>
            </div>
        </div>
    </div>

    <!-- Tag Modal -->
    <div id="tagModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">添加标签</h3>
            <input type="text" id="tagInput" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="请输入标签名称">
            <div class="flex space-x-3 mt-4">
                <button id="tagCancel" class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">取消</button>
                <button id="tagSave" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg">添加</button>
            </div>
        </div>
    </div>

    <script>
        let banks = @json($banks);
        let tags = @json($tags);

        function openIntroductionModal() {
            const currentText = document.getElementById('hiddenIntroduction').value;
            document.getElementById('introductionInput').value = currentText;
            document.getElementById('introductionModal').classList.add('active');
        }

        function openBankModal() {
            document.getElementById('bankInput').value = '';
            document.getElementById('bankModal').classList.add('active');
        }

        function openTagModal() {
            document.getElementById('tagInput').value = '';
            document.getElementById('tagModal').classList.add('active');
        }

        function removeBank(bank) {
            banks = banks.filter(b => b !== bank);
            updateBanksDisplay();
        }

        function removeTag(tag) {
            tags = tags.filter(t => t !== tag);
            updateTagsDisplay();
        }

        function updateBanksDisplay() {
            const container = document.getElementById('banksContainer');
            container.innerHTML = '';
            banks.forEach(bank => {
                container.innerHTML += `
                    <span class="tag text-gray-700 px-3 py-1 rounded-full flex items-center">
                        ${bank}
                        <i class="material-icons text-sm ml-1 cursor-pointer" onclick="removeBank('${bank}')">close</i>
                    </span>
                `;
            });
            document.getElementById('hiddenBanks').value = JSON.stringify(banks);
        }

        function updateTagsDisplay() {
            const container = document.getElementById('tagsContainer');
            container.innerHTML = '';
            tags.forEach(tag => {
                container.innerHTML += `
                    <span class="tag text-gray-700 px-3 py-1 rounded-full flex items-center">
                        ${tag}
                        <i class="material-icons text-sm ml-1 cursor-pointer" onclick="removeTag('${tag}')">close</i>
                    </span>
                `;
            });
            document.getElementById('hiddenTags').value = JSON.stringify(tags);
        }

        // Modal event listeners
        document.getElementById('introductionSave').addEventListener('click', () => {
            const value = document.getElementById('introductionInput').value;
            document.getElementById('hiddenIntroduction').value = value;
            document.getElementById('introductionText').textContent = value || '请详细介绍一下自己...';
            document.getElementById('introductionModal').classList.remove('active');
        });

        document.getElementById('introductionCancel').addEventListener('click', () => {
            document.getElementById('introductionModal').classList.remove('active');
        });

        document.getElementById('bankSave').addEventListener('click', () => {
            const value = document.getElementById('bankInput').value.trim();
            if (value && !banks.includes(value)) {
                banks.push(value);
                updateBanksDisplay();
            }
            document.getElementById('bankModal').classList.remove('active');
        });

        document.getElementById('bankCancel').addEventListener('click', () => {
            document.getElementById('bankModal').classList.remove('active');
        });

        document.getElementById('tagSave').addEventListener('click', () => {
            const value = document.getElementById('tagInput').value.trim();
            if (value && !tags.includes(value)) {
                tags.push(value);
                updateTagsDisplay();
            }
            document.getElementById('tagModal').classList.remove('active');
        });

        document.getElementById('tagCancel').addEventListener('click', () => {
            document.getElementById('tagModal').classList.remove('active');
        });

        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>