<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== 现有用户列表 ===" . PHP_EOL . PHP_EOL;

$users = \App\Models\User::select('id', 'name', 'email', 'user_type', 'is_verified_teacher')->get();

if ($users->isEmpty()) {
    echo "数据库中没有用户，需要创建测试账号。" . PHP_EOL;
} else {
    echo "共有 " . $users->count() . " 个用户：" . PHP_EOL . PHP_EOL;

    foreach ($users as $user) {
        echo "ID: {$user->id}" . PHP_EOL;
        echo "姓名: {$user->name}" . PHP_EOL;
        echo "邮箱: {$user->email}" . PHP_EOL;
        echo "类型: {$user->user_type}" . PHP_EOL;
        echo "已认证教师: " . ($user->is_verified_teacher ? '是' : '否') . PHP_EOL;
        echo "---" . PHP_EOL;
    }
}
