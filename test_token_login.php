<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// 先登录获取 token
echo "=== 1. 测试 API 登录获取 Token ===" . PHP_EOL;

$loginRequest = \Illuminate\Http\Request::create(
    '/api/v1/login',
    'POST',
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json'],
    json_encode([
        'email' => 'teacher1@cardbase.com',
        'password' => 'password123'
    ])
);

$loginRequest->headers->set('Content-Type', 'application/json');
$loginRequest->headers->set('Accept', 'application/json');

$loginResponse = $kernel->handle($loginRequest);
$loginData = json_decode($loginResponse->getContent(), true);

if (isset($loginData['data']['token'])) {
    $token = $loginData['data']['token'];
    echo "✓ 登录成功，获取到 Token: " . substr($token, 0, 20) . "..." . PHP_EOL . PHP_EOL;

    // 测试带 token 访问 Web 页面
    echo "=== 2. 测试通过 Token 访问 Web 页面 ===" . PHP_EOL;

    $webRequest = \Illuminate\Http\Request::create(
        '/?token=' . $token,
        'GET'
    );

    $webResponse = $kernel->handle($webRequest);

    echo "状态码: " . $webResponse->getStatusCode() . PHP_EOL;

    // 检查是否自动登录
    if ($webResponse->getStatusCode() == 200 || $webResponse->getStatusCode() == 302) {
        echo "✓ 可以正常访问！" . PHP_EOL;
        echo "中间件应该已经将用户自动登录" . PHP_EOL;
    } else {
        echo "✗ 访问失败" . PHP_EOL;
    }

} else {
    echo "✗ 登录失败" . PHP_EOL;
}

$kernel->terminate($loginRequest, $loginResponse);
