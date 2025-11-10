<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * 老师控制器
 */
class TeacherController extends Controller
{
    /**
     * 显示老师列表
     */
    public function index()
    {
        // 获取所有已认证的老师，并预加载服务信息
        $teachers = User::where('user_type', 'teacher')
            ->where('is_verified_teacher', true)
            ->with(['services' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('service_amount', 'asc');
            }])
            ->orderByDesc('current_rating')
            ->orderByDesc('completed_orders')
            ->get();

        return view('teachers.index', compact('teachers'));
    }

    /**
     * 显示老师详情
     */
    public function show(User $teacher)
    {
        // 确保是老师用户
        if ($teacher->user_type !== 'teacher') {
            abort(404);
        }

        // 加载相关数据
        $teacher->load([
            'services' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('service_amount', 'asc');
            },
            'teacherReviews' => function($query) {
                $query->with('user', 'order')
                      ->orderByDesc('created_at')
                      ->limit(10);
            }
        ]);

        return view('teachers.show', compact('teacher'));
    }
}
