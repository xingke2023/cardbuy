<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$newPassword = 'password123';

echo "=== 重置所有用户密码 ===" . PHP_EOL . PHP_EOL;

$users = \App\Models\User::all();

foreach ($users as $user) {
    $user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
    $user->save();

    echo "✓ {$user->name} ({$user->email}) - 密码已重置" . PHP_EOL;
}

echo PHP_EOL . "所有用户密码已重置为: {$newPassword}" . PHP_EOL;
echo PHP_EOL . "=== 测试账号信息 ===" . PHP_EOL . PHP_EOL;

echo "【普通用户】" . PHP_EOL;
echo "邮箱: customer@cardbase.com" . PHP_EOL;
echo "密码: password123" . PHP_EOL . PHP_EOL;

echo "【教师账号 1】" . PHP_EOL;
echo "邮箱: teacher1@cardbase.com" . PHP_EOL;
echo "密码: password123" . PHP_EOL . PHP_EOL;

echo "【教师账号 2】" . PHP_EOL;
echo "邮箱: teacher2@cardbase.com" . PHP_EOL;
echo "密码: password123" . PHP_EOL;
