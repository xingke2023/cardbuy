<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>我的邀请</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
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
<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white">
        <header class="bg-white flex items-center justify-between p-4 border-b">
            <a href="{{ route('profile.show') }}" class="w-8">
                <span class="material-icons text-blue-500">arrow_back_ios</span>
            </a>
            <h1 class="text-lg font-semibold">我的邀请</h1>
            <button class="text-blue-500">
                <span class="material-icons">more_horiz</span>
            </button>
        </header>

        <div class="p-4 bg-white border-b">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-gray-700">
                    <span class="material-icons text-gray-500 mr-2">person_outline</span>
                    <h2 class="text-base font-medium">我的邀请</h2>
                </div>
                <button id="inviteBtn" class="bg-blue-100 text-blue-500 text-sm font-semibold py-1.5 px-4 rounded-full hover:bg-blue-200 transition-colors">
                    邀请好友
                </button>
            </div>
        </div>

        <div class="flex text-center py-6 bg-white">
            <div class="w-1/2 border-r">
                <p class="text-gray-500 text-sm">已邀请注册用户</p>
                <p class="text-3xl font-bold mt-2">{{ $totalInvited }}</p>
            </div>
            <div class="w-1/2">
                <p class="text-gray-500 text-sm">订单分成</p>
                <p class="text-3xl font-bold text-red-500 mt-2">
                    <span class="text-lg">¥</span> {{ number_format($totalCommission, 0) }}
                </p>
            </div>
        </div>

        <div class="bg-white">
            <ul>
                @forelse($invitedUsers as $invitedUser)
                <li class="flex justify-between items-center p-4 border-b">
                    <span class="text-gray-800">{{ $invitedUser->name }}</span>
                    <span class="text-gray-500 text-sm">{{ $invitedUser->created_at->format('Y-m-d') }}</span>
                </li>
                @empty
                <li class="flex justify-center items-center p-8 text-gray-500">
                    <div class="text-center">
                        <span class="material-icons text-6xl text-gray-300 mb-4">person_add</span>
                        <p>暂无邀请用户</p>
                    </div>
                </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Invite Modal -->
    <div id="inviteModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-sm w-full">
            <div class="text-center">
                <h3 class="text-lg font-semibold mb-4">邀请好友</h3>
                <p class="text-gray-600 text-sm mb-4">分享您的专属邀请链接给好友，好友注册成功后您将获得奖励</p>
                
                <div class="bg-gray-100 p-3 rounded-lg mb-4">
                    <p class="text-xs text-gray-500 mb-2">您的邀请链接</p>
                    <p id="inviteLink" class="text-sm text-blue-600 break-all">
                        {{ url('/register?ref=' . $user->id) }}
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button id="copyBtn" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg text-sm font-semibold hover:bg-blue-600 transition-colors">
                        复制链接
                    </button>
                    <button id="shareBtn" class="flex-1 bg-green-500 text-white py-2 px-4 rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors">
                        分享
                    </button>
                </div>

                <button id="closeModal" class="mt-4 text-gray-500 text-sm">关闭</button>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const inviteBtn = document.getElementById('inviteBtn');
        const inviteModal = document.getElementById('inviteModal');
        const closeModal = document.getElementById('closeModal');
        const copyBtn = document.getElementById('copyBtn');
        const shareBtn = document.getElementById('shareBtn');
        const inviteLink = document.getElementById('inviteLink').textContent.trim();

        inviteBtn.addEventListener('click', () => {
            inviteModal.classList.add('active');
        });

        closeModal.addEventListener('click', () => {
            inviteModal.classList.remove('active');
        });

        inviteModal.addEventListener('click', (e) => {
            if (e.target === inviteModal) {
                inviteModal.classList.remove('active');
            }
        });

        // Copy link functionality
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(inviteLink);
                copyBtn.textContent = '已复制!';
                copyBtn.classList.add('bg-green-500');
                copyBtn.classList.remove('bg-blue-500');
                
                setTimeout(() => {
                    copyBtn.textContent = '复制链接';
                    copyBtn.classList.remove('bg-green-500');
                    copyBtn.classList.add('bg-blue-500');
                }, 2000);
            } catch (err) {
                console.error('复制失败:', err);
                alert('复制失败，请手动复制链接');
            }
        });

        // Share functionality
        shareBtn.addEventListener('click', () => {
            if (navigator.share) {
                navigator.share({
                    title: '价值派·开户易 - 邀请注册',
                    text: '我邀请您注册价值派·开户易，专业的银行开户服务平台',
                    url: inviteLink
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                copyBtn.click();
            }
        });
    </script>
</body>
</html>