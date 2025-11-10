<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>编辑基本资料</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
        .modal {
            display: none;
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto max-w-sm">
        <div class="bg-white shadow-sm">
            <header class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center">
                    <a href="{{ route('profile.show') }}" class="flex items-center text-blue-500">
                        <i class="material-icons">arrow_back_ios</i>
                        <span class="text-lg">返回</span>
                    </a>
                </div>
                <h1 class="text-xl font-semibold">编辑基本资料</h1>
                <div class="w-12"></div>
            </header>

            <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                @csrf
                @method('PATCH')
                
                <main class="bg-white">
                    <ul class="divide-y divide-gray-200">
                        <li class="flex items-center justify-between p-4">
                            <span class="text-lg text-gray-700">头像</span>
                            <img alt="User avatar" 
                                 class="w-12 h-12 rounded-full" 
                                 src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"/>
                        </li>

                        <li class="flex items-center justify-between p-4 cursor-pointer" onclick="openEditModal('name', '{{ $user->name }}')">
                            <span class="text-lg text-gray-700">昵称</span>
                            <div class="flex items-center">
                                <span class="text-lg text-gray-500">{{ $user->name }}</span>
                                <i class="material-icons text-gray-400 ml-2">arrow_forward_ios</i>
                            </div>
                        </li>

                        <li class="flex items-center justify-between p-4 cursor-pointer" onclick="openSelectModal('gender', '{{ $user->gender ?? '' }}')">
                            <span class="text-lg text-gray-700">性别</span>
                            <div class="flex items-center">
                                <span class="text-lg text-gray-500">
                                    @if($user->gender === 'male')
                                        男
                                    @elseif($user->gender === 'female')
                                        女
                                    @else
                                        请选择
                                    @endif
                                </span>
                                <i class="material-icons text-gray-400 ml-2">arrow_forward_ios</i>
                            </div>
                        </li>

                        <li class="flex items-center justify-between p-4 cursor-pointer" onclick="openEditModal('organization', '{{ $user->organization ?? '' }}')">
                            <span class="text-lg text-gray-700">任职机构</span>
                            <div class="flex items-center">
                                <span class="text-lg {{ $user->organization ? 'text-gray-500' : 'text-gray-400' }}">
                                    {{ $user->organization ?: '请输入任职机构' }}
                                </span>
                                <i class="material-icons text-gray-400 ml-2">arrow_forward_ios</i>
                            </div>
                        </li>

                        <li class="flex items-center justify-between p-4 cursor-pointer" onclick="openEditModal('city', '{{ $user->city ?? '' }}')">
                            <span class="text-lg text-gray-700">城市</span>
                            <div class="flex items-center">
                                <span class="text-lg {{ $user->city ? 'text-gray-500' : 'text-gray-400' }}">
                                    {{ $user->city ?: '选择常驻城市' }}
                                </span>
                                <i class="material-icons text-gray-400 ml-2">arrow_forward_ios</i>
                            </div>
                        </li>

                        @if($user->user_type === 'teacher')
                        <li class="flex items-center justify-between p-4 cursor-pointer" onclick="openEditModal('specialties', '{{ $user->specialties ?? '' }}')">
                            <span class="text-lg text-gray-700">专业领域</span>
                            <div class="flex items-center">
                                <span class="text-lg {{ $user->specialties ? 'text-gray-500' : 'text-gray-400' }}">
                                    {{ $user->specialties ?: '请输入专业领域' }}
                                </span>
                                <i class="material-icons text-gray-400 ml-2">arrow_forward_ios</i>
                            </div>
                        </li>

                        <li class="flex items-center justify-between p-4 cursor-pointer" onclick="openEditModal('serviceable_banks', '{{ $user->serviceable_banks ?? '' }}')">
                            <span class="text-lg text-gray-700">可服务银行</span>
                            <div class="flex items-center">
                                <span class="text-lg {{ $user->serviceable_banks ? 'text-gray-500' : 'text-gray-400' }}">
                                    {{ $user->serviceable_banks ?: '请输入可服务银行' }}
                                </span>
                                <i class="material-icons text-gray-400 ml-2">arrow_forward_ios</i>
                            </div>
                        </li>

                        <li class="p-4 cursor-pointer" onclick="openTextareaModal('personal_introduction', '{{ $user->personal_introduction ?? '' }}')">
                            <div class="flex items-center justify-between">
                                <span class="text-lg text-gray-700">个人介绍</span>
                                <i class="material-icons text-gray-400">arrow_forward_ios</i>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                {{ $user->personal_introduction ?: '请输入个人介绍' }}
                            </p>
                        </li>
                        @endif
                    </ul>

                    @if ($errors->any())
                        <div class="m-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('status') === 'profile-updated')
                        <div class="m-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            资料更新成功！
                        </div>
                    @endif
                </main>

                <!-- Hidden inputs for form data -->
                <input type="hidden" name="name" value="{{ $user->name }}" id="hiddenName">
                <input type="hidden" name="gender" value="{{ $user->gender }}" id="hiddenGender">
                <input type="hidden" name="organization" value="{{ $user->organization }}" id="hiddenOrganization">
                <input type="hidden" name="city" value="{{ $user->city }}" id="hiddenCity">
                @if($user->user_type === 'teacher')
                <input type="hidden" name="specialties" value="{{ $user->specialties }}" id="hiddenSpecialties">
                <input type="hidden" name="serviceable_banks" value="{{ $user->serviceable_banks }}" id="hiddenServiceableBanks">
                <input type="hidden" name="personal_introduction" value="{{ $user->personal_introduction }}" id="hiddenPersonalIntroduction">
                @endif
            </form>
        </div>

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white">
            <button type="submit" form="profileForm" class="w-full bg-blue-500 text-white py-3 rounded-lg text-lg font-semibold hover:bg-blue-600 transition-colors">
                保存
            </button>
        </div>

        <!-- Add bottom spacing for fixed button -->
        <div class="h-20"></div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
            <h3 id="editModalTitle" class="text-lg font-semibold mb-4">编辑</h3>
            <input type="text" id="editModalInput" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="请输入内容">
            <div class="flex space-x-3 mt-4">
                <button id="editModalCancel" class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">取消</button>
                <button id="editModalSave" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg">确定</button>
            </div>
        </div>
    </div>

    <!-- Select Modal -->
    <div id="selectModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
            <h3 id="selectModalTitle" class="text-lg font-semibold mb-4">选择性别</h3>
            <div class="space-y-2">
                <button class="w-full p-3 text-left border border-gray-300 rounded-lg hover:bg-gray-50" onclick="selectGender('male')">男</button>
                <button class="w-full p-3 text-left border border-gray-300 rounded-lg hover:bg-gray-50" onclick="selectGender('female')">女</button>
            </div>
            <button id="selectModalCancel" class="w-full mt-4 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">取消</button>
        </div>
    </div>

    <!-- Textarea Modal -->
    <div id="textareaModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
            <h3 id="textareaModalTitle" class="text-lg font-semibold mb-4">编辑个人介绍</h3>
            <textarea id="textareaModalInput" rows="6" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 resize-none" placeholder="请输入个人介绍"></textarea>
            <div class="flex space-x-3 mt-4">
                <button id="textareaModalCancel" class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">取消</button>
                <button id="textareaModalSave" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg">确定</button>
            </div>
        </div>
    </div>

    <script>
        let currentField = '';

        function openEditModal(field, currentValue) {
            currentField = field;
            document.getElementById('editModalTitle').textContent = `编辑${getFieldName(field)}`;
            document.getElementById('editModalInput').value = currentValue || '';
            document.getElementById('editModal').classList.add('active');
        }

        function openSelectModal(field, currentValue) {
            currentField = field;
            document.getElementById('selectModal').classList.add('active');
        }

        function openTextareaModal(field, currentValue) {
            currentField = field;
            document.getElementById('textareaModalInput').value = currentValue || '';
            document.getElementById('textareaModal').classList.add('active');
        }

        function getFieldName(field) {
            const names = {
                'name': '昵称',
                'organization': '任职机构',
                'city': '城市',
                'specialties': '专业领域',
                'serviceable_banks': '可服务银行',
                'personal_introduction': '个人介绍'
            };
            return names[field] || field;
        }

        function selectGender(gender) {
            updateField('gender', gender);
            document.getElementById('selectModal').classList.remove('active');
        }

        function updateField(field, value) {
            document.getElementById(`hidden${field.charAt(0).toUpperCase() + field.slice(1)}`).value = value;
            
            // Update display
            const displayElement = document.querySelector(`[onclick*="${field}"] .text-gray-500, [onclick*="${field}"] .text-gray-400`);
            if (displayElement) {
                displayElement.textContent = value || getPlaceholder(field);
                displayElement.className = value ? 'text-lg text-gray-500' : 'text-lg text-gray-400';
            }
        }

        function getPlaceholder(field) {
            const placeholders = {
                'name': '请输入昵称',
                'gender': '请选择',
                'organization': '请输入任职机构',
                'city': '选择常驻城市',
                'specialties': '请输入专业领域',
                'serviceable_banks': '请输入可服务银行',
                'personal_introduction': '请输入个人介绍'
            };
            return placeholders[field] || '';
        }

        // Modal event listeners
        document.getElementById('editModalSave').addEventListener('click', () => {
            const value = document.getElementById('editModalInput').value;
            updateField(currentField, value);
            document.getElementById('editModal').classList.remove('active');
        });

        document.getElementById('editModalCancel').addEventListener('click', () => {
            document.getElementById('editModal').classList.remove('active');
        });

        document.getElementById('selectModalCancel').addEventListener('click', () => {
            document.getElementById('selectModal').classList.remove('active');
        });

        document.getElementById('textareaModalSave').addEventListener('click', () => {
            const value = document.getElementById('textareaModalInput').value;
            updateField(currentField, value);
            document.getElementById('textareaModal').classList.remove('active');
        });

        document.getElementById('textareaModalCancel').addEventListener('click', () => {
            document.getElementById('textareaModal').classList.remove('active');
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