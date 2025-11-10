<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

echo "=== 测试 API 登录接口 ===" . PHP_EOL . PHP_EOL;

// 模拟 POST 请求到登录接口
$data = [
    'email' => 'teacher1@cardbase.com',
    'password' => 'password123'
];

$request = \Illuminate\Http\Request::create(
    '/api/v1/login',
    'POST',
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json'],
    json_encode($data)
);

$request->headers->set('Content-Type', 'application/json');
$request->headers->set('Accept', 'application/json');

try {
    $response = $kernel->handle($request);

    echo "状态码: " . $response->getStatusCode() . PHP_EOL;
    echo "响应内容: " . PHP_EOL;
    echo $response->getContent() . PHP_EOL;

    $data = json_decode($response->getContent(), true);

    if (isset($data['success']) && $data['success']) {
        echo PHP_EOL . "✓ 登录成功！" . PHP_EOL;
        echo "Token: " . $data['data']['token'] . PHP_EOL;
    } else {
        echo PHP_EOL . "✗ 登录失败" . PHP_EOL;
    }

} catch (\Exception $e) {
    echo "错误: " . $e->getMessage() . PHP_EOL;
}

$kernel->terminate($request, $response);
