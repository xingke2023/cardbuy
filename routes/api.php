<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| 这些路由为小程序提供 RESTful API 接口
| 所有 API 响应都是 JSON 格式
|
*/

// 公开接口（无需认证）
Route::prefix('v1')->group(function () {

    // 认证接口
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/wechat/login', [AuthController::class, 'wechatLogin']); // 微信登录

    // 教师列表（公开）
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);
});

// 需要认证的接口
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // 用户相关
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/bind-phone', [AuthController::class, 'bindPhone']); // 绑定手机号

    // 服务管理（教师）
    Route::get('/my/services', [ServiceController::class, 'myServices']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::patch('/services/{id}/toggle-status', [ServiceController::class, 'toggleStatus']);

    // 订单管理
    Route::get('/my/orders', [OrderController::class, 'myOrders']); // 用户订单
    Route::get('/my/teacher-orders', [OrderController::class, 'teacherOrders']); // 教师订单
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // 订单操作
    Route::post('/orders/{id}/accept', [OrderController::class, 'accept']); // 教师接受
    Route::post('/orders/{id}/reject', [OrderController::class, 'reject']); // 教师拒绝
    Route::patch('/orders/{id}/complete', [OrderController::class, 'complete']); // 教师完成
    Route::patch('/orders/{id}/confirm', [OrderController::class, 'confirm']); // 用户确认
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel']); // 取消

    // 教师申请
    Route::post('/teacher/apply', [AuthController::class, 'applyTeacher']);
});
