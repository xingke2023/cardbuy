<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * 获取我的服务列表
     */
    public function myServices(Request $request)
    {
        $services = $request->user()->services()
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * 创建服务
     */
    public function store(Request $request)
    {
        if ($request->user()->user_type !== 'teacher') {
            return response()->json([
                'success' => false,
                'message' => '只有教师可以创建服务',
            ], 403);
        }

        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'service_name' => 'required|string|max:255',
            'service_details' => 'required|string',
            'service_amount' => 'required|numeric|min:0',
        ]);

        $service = $request->user()->services()->create([
            'service_type' => $validated['service_type'],
            'service_name' => $validated['service_name'],
            'service_details' => $validated['service_details'],
            'service_amount' => $validated['service_amount'],
            'is_active' => true,
            'completed_orders_count' => 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => '服务创建成功',
        ], 201);
    }

    /**
     * 获取服务详情
     */
    public function show(Request $request, $id)
    {
        $service = Service::with('user')->findOrFail($id);

        if ($service->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权访问',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $service,
        ]);
    }

    /**
     * 更新服务
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        if ($service->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        $validated = $request->validate([
            'service_type' => 'sometimes|string|max:255',
            'service_name' => 'sometimes|string|max:255',
            'service_details' => 'sometimes|string',
            'service_amount' => 'sometimes|numeric|min:0',
        ]);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'data' => $service->fresh(),
            'message' => '服务更新成功',
        ]);
    }

    /**
     * 删除服务
     */
    public function destroy(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        if ($service->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        $orderCount = $service->orders()->count();
        if ($orderCount > 0) {
            return response()->json([
                'success' => false,
                'message' => '该服务有关联订单，无法删除',
            ], 400);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => '服务删除成功',
        ]);
    }

    /**
     * 切换服务状态
     */
    public function toggleStatus(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        if ($service->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        $service->update([
            'is_active' => !$service->is_active
        ]);

        return response()->json([
            'success' => true,
            'data' => $service->fresh(),
            'message' => $service->is_active ? '服务已开启' : '服务已关闭',
        ]);
    }
}
