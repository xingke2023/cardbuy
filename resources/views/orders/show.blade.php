<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>服务订单详情</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8fafc;
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
    @if(Auth::user()->user_type === 'teacher' && $order->status === 'pending')
        @include('orders.teacher-pending-view')
    @elseif(Auth::user()->user_type === 'teacher' && $order->status === 'accepted')
        @include('orders.teacher-accepted-view')
    @else
        @include('orders.customer-view')
    @endif
</body>
</html>