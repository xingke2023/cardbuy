<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 创建订单
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'expected_completion_date' => 'required|date|after_or_equal:today',
            'account_opening_area' => 'required|string|max:255',
            'share_contact_info' => 'required|boolean',
            'contact_info' => 'nullable|string|max:255',
            'other_requirements' => 'nullable|string|max:1000',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        if (!$service->is_active) {
            return response()->json([
                'success' => false,
                'message' => '该服务已停用',
            ], 400);
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'teacher_id' => $service->user_id,
            'service_id' => $service->id,
            'status' => 'pending',
            'expected_completion_date' => $validated['expected_completion_date'],
            'account_opening_area' => $validated['account_opening_area'],
            'share_contact_info' => $validated['share_contact_info'],
            'contact_info' => $validated['contact_info'] ?? null,
            'other_requirements' => $validated['other_requirements'] ?? null,
            'order_created_at' => now(),
            'payment_amount' => $service->service_amount,
            'is_paid' => false,
            'paid_amount' => 0,
        ]);

        $request->user()->increment('initiated_orders');

        return response()->json([
            'success' => true,
            'data' => $order->load(['service', 'teacher']),
            'message' => '订单创建成功',
        ], 201);
    }

    /**
     * 获取用户的订单列表
     */
    public function myOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['teacher', 'service'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * 获取教师的订单列表
     */
    public function teacherOrders(Request $request)
    {
        if ($request->user()->user_type !== 'teacher') {
            return response()->json([
                'success' => false,
                'message' => '只有教师可以查看服务订单',
            ], 403);
        }

        $orders = Order::where('teacher_id', $request->user()->id)
            ->with(['user', 'service'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * 获取订单详情
     */
    public function show(Request $request, $id)
    {
        $order = Order::with(['user', 'teacher', 'service'])->findOrFail($id);

        // 权限检查
        if ($order->user_id !== $request->user()->id && $order->teacher_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权访问该订单',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * 教师接受订单
     */
    public function accept(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($request->user()->user_type !== 'teacher' || $order->teacher_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => '订单状态不允许接受',
            ], 400);
        }

        $validated = $request->validate([
            'teacher_recommended_bank' => 'nullable|string|max:255',
            'estimated_online_appointment_time' => 'nullable|date',
            'teacher_message' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'status' => 'accepted',
            'teacher_recommended_bank' => $validated['teacher_recommended_bank'] ?? null,
            'estimated_online_appointment_time' => $validated['estimated_online_appointment_time'] ?? null,
            'teacher_message' => $validated['teacher_message'] ?? null,
            'accepted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $order->fresh(),
            'message' => '订单已接受',
        ]);
    }

    /**
     * 教师拒绝订单
     */
    public function reject(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($request->user()->user_type !== 'teacher' || $order->teacher_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => '订单状态不允许拒绝',
            ], 400);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $order->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $order->fresh(),
            'message' => '订单已拒绝',
        ]);
    }

    /**
     * 教师完成订单
     */
    public function complete(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($request->user()->user_type !== 'teacher' || $order->teacher_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        if ($order->status !== 'accepted') {
            return response()->json([
                'success' => false,
                'message' => '订单状态不允许完成',
            ], 400);
        }

        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $request->user()->increment('completed_orders');

        if ($order->service) {
            $order->service->increment('completed_orders_count');
        }

        return response()->json([
            'success' => true,
            'data' => $order->fresh(),
            'message' => '订单已完成',
        ]);
    }

    /**
     * 用户确认订单
     */
    public function confirm(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        if ($order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => '订单状态不允许确认',
            ], 400);
        }

        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $order->fresh(),
            'message' => '订单已确认',
        ]);
    }

    /**
     * 取消订单
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->teacher_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => '无权操作',
            ], 403);
        }

        if ($order->status !== 'accepted') {
            return response()->json([
                'success' => false,
                'message' => '订单状态不允许取消',
            ], 400);
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $order->fresh(),
            'message' => '订单已取消',
        ]);
    }
}
