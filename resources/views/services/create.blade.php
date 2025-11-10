<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ isset($service) ? '编辑服务' : '添加服务' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-md mx-auto bg-white">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="px-4 py-3 flex items-center justify-between">
                <a class="flex items-center text-blue-500" href="{{ isset($service) ? route('services.show', $service) : route('services.my') }}">
                    <i class="material-icons">chevron_left</i>
                    <span class="text-lg">返回</span>
                </a>
                <h1 class="text-xl font-semibold">{{ isset($service) ? '编辑服务' : '添加服务' }}</h1>
                <button class="text-blue-500">
                    <i class="material-icons">more_horiz</i>
                </button>
            </div>
        </header>

        <form action="{{ isset($service) ? route('services.update', $service) : route('services.store') }}" method="POST" id="service-form">
            @csrf
            @if(isset($service))
                @method('PATCH')
            @endif
            <main class="py-4 space-y-4">
                <div class="bg-white">
                    <div class="px-4 py-3 border-b">
                        <label class="text-gray-800 text-lg font-medium" for="service_type">服务类型</label>
                        <select name="service_type" id="service_type" class="w-full mt-2 p-2 border-none bg-transparent text-gray-800 focus:outline-none" required>
                            <option value="">请选择</option>
                            <option value="银行开户" {{ (isset($service) && $service->service_type === '银行开户') || old('service_type') === '银行开户' ? 'selected' : '' }}>银行开户</option>
                            <option value="投资理财" {{ (isset($service) && $service->service_type === '投资理财') || old('service_type') === '投资理财' ? 'selected' : '' }}>投资理财</option>
                            <option value="保险咨询" {{ (isset($service) && $service->service_type === '保险咨询') || old('service_type') === '保险咨询' ? 'selected' : '' }}>保险咨询</option>
                            <option value="其他" {{ (isset($service) && $service->service_type === '其他') || old('service_type') === '其他' ? 'selected' : '' }}>其他</option>
                        </select>
                    </div>
                    
                    <div class="px-4 py-3 border-b">
                        <label class="text-gray-800 text-lg font-medium" for="service_name">服务标题</label>
                        <input type="text" 
                               name="service_name" 
                               id="service_name" 
                               class="w-full mt-2 p-2 border-none bg-transparent focus:outline-none" 
                               placeholder="请用精简语言描述服务内容，如：全港区域内，开通个人银行账户，1个"
                               value="{{ old('service_name', isset($service) ? $service->service_name : '') }}"
                               required>
                    </div>
                    
                    <div class="px-4 py-3 border-b">
                        <label class="text-gray-800 text-lg font-medium" for="service_details">服务详情</label>
                        <textarea name="service_details" 
                                  id="service_details" 
                                  rows="8" 
                                  class="w-full mt-2 p-2 border-none bg-transparent focus:outline-none resize-none"
                                  placeholder="请详细介绍您的服务内容，建议描述清楚以下内容：&#10;&#10;【适合用户】如第一次到香港开户&#10;&#10;【服务优势】如全程陪同，保障完成线下面签或最终发卡激活&#10;&#10;【个人优势】如您的优势资源、特殊渠道等，能保障开户更高效、成功率更高"
                                  required>{{ old('service_details', isset($service) ? $service->service_details : '') }}</textarea>
                    </div>
                </div>

                <div class="bg-white">
                    <div class="px-4 py-3 border-b">
                        <div class="flex items-center justify-between">
                            <label class="text-gray-800 text-lg font-medium" for="service_amount">设置价格</label>
                            <span class="text-gray-500 text-sm">其中，20%为平台服务费和中间人介绍费</span>
                        </div>
                        <div class="mt-2 flex items-center">
                            <span class="text-gray-800 text-lg mr-2">¥</span>
                            <input type="number" 
                                   name="service_amount" 
                                   id="service_amount" 
                                   class="flex-1 p-2 border-none bg-transparent focus:outline-none text-lg"
                                   placeholder="请输入价格"
                                   value="{{ old('service_amount', isset($service) ? $service->service_amount : '') }}"
                                   min="0"
                                   step="0.01"
                                   required>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mx-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </main>

            <footer class="fixed bottom-0 left-0 right-0 max-w-md mx-auto">
                <button type="submit" class="w-full bg-blue-500 text-white py-4 text-lg font-semibold hover:bg-blue-600 transition-colors">
                    {{ isset($service) ? '保存修改' : '预览并发布' }}
                </button>
            </footer>
        </form>

        <!-- Add bottom spacing for fixed footer -->
        <div class="h-20"></div>
    </div>
</body>
</html>