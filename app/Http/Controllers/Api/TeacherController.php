<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * 获取教师列表
     */
    public function index(Request $request)
    {
        $query = User::where('user_type', 'teacher')
            ->where('is_verified_teacher', true)
            ->with(['services' => function($q) {
                $q->where('is_active', true)
                  ->orderBy('service_amount', 'asc');
            }]);

        // 支持搜索
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('bio', 'like', "%{$keyword}%");
            });
        }

        // 排序
        $query->orderByDesc('current_rating')
              ->orderByDesc('completed_orders');

        // 分页
        $perPage = $request->get('per_page', 10);
        $teachers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $teachers,
        ]);
    }

    /**
     * 获取教师详情
     */
    public function show($id)
    {
        $teacher = User::where('id', $id)
            ->where('user_type', 'teacher')
            ->with([
                'services' => function($q) {
                    $q->where('is_active', true)
                      ->orderBy('service_amount', 'asc');
                },
                'teacherReviews' => function($q) {
                    $q->with('user', 'order')
                      ->orderByDesc('created_at')
                      ->limit(10);
                }
            ])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $teacher,
        ]);
    }
}
