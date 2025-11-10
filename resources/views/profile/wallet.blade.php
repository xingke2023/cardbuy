<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>我的钱包</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
        .dropdown {
            display: none;
        }
        .dropdown.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto max-w-sm">
        <header class="bg-white p-4 flex justify-between items-center">
            <a href="{{ route('profile.show') }}">
                <span class="material-icons text-blue-500">arrow_back_ios</span>
            </a>
            <h1 class="text-xl font-semibold">我的钱包</h1>
            <button class="text-gray-500">
                <span class="material-icons">more_horiz</span>
            </button>
        </header>

        <main class="bg-white">
            <div class="text-center py-12">
                <p class="text-5xl font-bold text-blue-500">¥ {{ number_format($totalBalance, 0) }}</p>
                <p class="text-gray-500 mt-2">可提现: ¥ {{ number_format($availableBalance, 0) }}</p>
            </div>

            <div class="px-4 py-4 bg-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">收支明细</h2>
                    <div class="relative">
                        <button id="filterBtn" class="border border-blue-500 text-blue-500 px-4 py-1 rounded-full flex items-center">
                            <span id="filterText">
                                @switch($currentFilter)
                                    @case('income')
                                        全部收入
                                        @break
                                    @case('income_service')
                                        收入-服务收益
                                        @break
                                    @case('income_referral')
                                        收入-好友分成
                                        @break
                                    @case('expense')
                                        全部支出
                                        @break
                                    @default
                                        全部
                                @endswitch
                            </span>
                            <span class="material-icons text-sm ml-1">arrow_drop_down</span>
                        </button>
                        
                        <div id="filterDropdown" class="dropdown absolute right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 min-w-36">
                            <a href="{{ route('profile.wallet', ['filter' => 'all']) }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 {{ $currentFilter === 'all' ? 'text-blue-500 bg-blue-50' : 'text-gray-700' }}">
                                全部
                            </a>
                            <a href="{{ route('profile.wallet', ['filter' => 'income']) }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 {{ $currentFilter === 'income' ? 'text-blue-500 bg-blue-50' : 'text-gray-700' }}">
                                全部收入
                            </a>
                            <a href="{{ route('profile.wallet', ['filter' => 'income_service']) }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 {{ $currentFilter === 'income_service' ? 'text-blue-500 bg-blue-50' : 'text-gray-700' }}">
                                收入-服务收益
                            </a>
                            <a href="{{ route('profile.wallet', ['filter' => 'income_referral']) }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 {{ $currentFilter === 'income_referral' ? 'text-blue-500 bg-blue-50' : 'text-gray-700' }}">
                                收入-好友分成
                            </a>
                            <a href="{{ route('profile.wallet', ['filter' => 'expense']) }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 {{ $currentFilter === 'expense' ? 'text-blue-500 bg-blue-50' : 'text-gray-700' }}">
                                全部支出
                            </a>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($transactions as $transaction)
                    <div class="bg-white p-4 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-semibold {{ $transaction['type'] === 'income' ? 'text-green-500' : 'text-red-500' }}">
                                {{ $transaction['type'] === 'income' ? '收入' : '支出' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">{{ $transaction['description'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $transaction['created_at'] }}</p>
                        </div>
                        <p class="font-semibold {{ $transaction['type'] === 'income' ? 'text-green-500' : 'text-red-500' }}">
                            {{ $transaction['type'] === 'income' ? '+' : '-' }} {{ $transaction['amount'] }}
                        </p>
                    </div>
                    @empty
                    <div class="bg-white p-8 rounded-lg text-center text-gray-500">
                        <span class="material-icons text-6xl text-gray-300 mb-4">account_balance_wallet</span>
                        <p>暂无交易记录</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <script>
        // Dropdown functionality
        const filterBtn = document.getElementById('filterBtn');
        const filterDropdown = document.getElementById('filterDropdown');

        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            filterDropdown.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            filterDropdown.classList.remove('active');
        });

        // Prevent dropdown from closing when clicking inside
        filterDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    </script>
</body>
</html>