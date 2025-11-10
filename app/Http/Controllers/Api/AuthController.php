<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * 用户登录
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['邮箱或密码错误'],
            ]);
        }

        // 创建 token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => '登录成功',
        ]);
    }

    /**
     * 用户注册
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => 'customer',
            'is_verified_teacher' => false,
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => '注册成功',
        ], 201);
    }

    /**
     * 获取当前用户信息
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    /**
     * 用户登出
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => '登出成功',
        ]);
    }

    /**
     * 更新用户资料
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'sometimes|string',
        ]);

        $request->user()->update($validated);

        return response()->json([
            'success' => true,
            'data' => $request->user()->fresh(),
            'message' => '资料更新成功',
        ]);
    }

    /**
     * 申请成为教师
     */
    public function applyTeacher(Request $request)
    {
        $validated = $request->validate([
            'real_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'teaching_experience' => 'required|string',
            'specialties' => 'required|string',
        ]);

        // 这里可以创建教师申请记录
        // 暂时直接更新用户类型（实际应该需要审核流程）
        $user = $request->user();
        $user->update([
            'user_type' => 'teacher',
            'is_verified_teacher' => false, // 需要管理员审核
        ]);

        return response()->json([
            'success' => true,
            'message' => '申请已提交，等待审核',
        ]);
    }

    /**
     * 微信小程序登录
     */
    public function wechatLogin(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'nickName' => 'nullable|string',
            'avatarUrl' => 'nullable|string',
        ]);

        $appid = env('WECHAT_MINI_PROGRAM_APPID');
        $secret = env('WECHAT_MINI_PROGRAM_SECRET');
        $code = $request->code;

        // 调用微信 API 获取 session_key 和 openid
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['errcode']) && $data['errcode'] != 0) {
            return response()->json([
                'success' => false,
                'message' => '微信登录失败: ' . ($data['errmsg'] ?? '未知错误'),
            ], 400);
        }

        $openid = $data['openid'];
        $sessionKey = $data['session_key'];

        // 查找或创建用户
        $user = User::where('wechat', $openid)->first();

        $isNewUser = false;
        if (!$user) {
            // 第一次登录，创建新用户
            $isNewUser = true;
            $user = User::create([
                'name' => $request->nickName ?? '微信用户',
                'email' => $openid . '@wechat.cardbase.com', // 临时邮箱
                'password' => Hash::make(str()->random(32)), // 随机密码
                'wechat' => $openid,
                'avatar' => $request->avatarUrl,
                'user_type' => 'customer',
                'is_verified_teacher' => false,
            ]);
        } else {
            // 已有用户，更新昵称和头像（如果提供了的话）
            $updateData = ['session_key' => $sessionKey];
            if ($request->nickName) {
                $updateData['name'] = $request->nickName;
            }
            if ($request->avatarUrl) {
                $updateData['avatar'] = $request->avatarUrl;
            }
            $user->update($updateData);
        }

        // 生成 token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
                'is_new_user' => $isNewUser,
            ],
            'message' => '登录成功',
        ]);
    }

    /**
     * 绑定手机号
     */
    public function bindPhone(Request $request)
    {
        $request->validate([
            'encryptedData' => 'required|string',
            'iv' => 'required|string',
        ]);

        // 这里需要实现微信数据解密
        // 简化版本，可以让用户直接输入手机号
        $validated = $request->validate([
            'phone' => 'sometimes|string|max:20',
        ]);

        if (isset($validated['phone'])) {
            $request->user()->update([
                'phone' => $validated['phone'],
            ]);

            return response()->json([
                'success' => true,
                'message' => '手机号绑定成功',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '请提供手机号',
        ], 400);
    }
}
