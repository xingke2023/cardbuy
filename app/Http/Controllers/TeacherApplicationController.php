<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * 老师申请控制器
 */
class TeacherApplicationController extends Controller
{
    /**
     * 显示申请成为老师页面
     */
    public function apply(): View
    {
        $user = Auth::user();
        
        // 如果已经是认证老师，重定向到我的页面
        if ($user->user_type === 'teacher' && $user->is_verified_teacher) {
            return redirect()->route('profile.show')->with('message', '您已经是认证老师');
        }
        
        // 模拟用户的服务数据
        $services = collect([
            [
                'id' => 1,
                'service_name' => '全港区域内，开通个人银行账户, 2个',
                'service_details' => '我是服务介绍前两行，我是服务介绍前两行，我是服务介绍前两行，我是服务介绍前两行...',
                'service_amount' => 900,
                'completed_orders_count' => 0,
                'status' => 'pending_review'
            ]
        ]);
        
        // 模拟擅长银行
        $banks = ['中国银行', '渣打', '汇丰'];
        
        // 模拟标签
        $tags = ['个人开户', '家庭理财'];
        
        return view('teacher.apply', [
            'user' => $user,
            'services' => $services,
            'banks' => $banks,
            'tags' => $tags,
        ]);
    }

    /**
     * 提交老师申请
     */
    public function submit(Request $request)
    {
        $user = Auth::user();
        
        // 验证请求数据
        $validated = $request->validate([
            'personal_introduction' => 'required|string|max:2000',
            'banks' => 'required|array|min:1',
            'tags' => 'required|array|min:1',
            'services' => 'required|array|min:1',
        ], [
            'personal_introduction.required' => '请填写个人介绍',
            'banks.required' => '请至少选择一个擅长银行',
            'tags.required' => '请至少添加一个标签',
            'services.required' => '请至少添加一个服务',
        ]);

        // 更新用户信息
        $user->update([
            'user_type' => 'teacher',
            'is_verified_teacher' => false, // 待审核状态
            'personal_introduction' => $validated['personal_introduction'],
            'serviceable_banks' => implode(',', $validated['banks']),
            'specialties' => implode(',', $validated['tags']),
        ]);

        // 这里可以创建服务记录、发送通知等

        return redirect()->route('profile.show')->with('success', '申请已提交，请等待审核');
    }
}
