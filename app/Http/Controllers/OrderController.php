<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 订单控制器
 */
class OrderController extends Controller
{
    /**
     * 显示创建订单表单
     */
    public function create(Service $service)
    {
        // 确保服务是活跃的
        if (!$service->is_active) {
            abort(404, '该服务已停用');
        }

        // 确保服务提供者是老师
        if ($service->user->user_type !== 'teacher') {
            abort(404, '无效的服务');
        }

        // 加载服务提供者信息
        $service->load('user');

        return view('orders.create', compact('service'));
    }

    /**
     * 存储订单
     */
    public function store(Request $request, Service $service)
    {
        // 验证请求数据
        $validated = $request->validate([
            'expected_completion_date' => 'required|date|after_or_equal:today',
            'account_opening_area' => 'required|string|max:255',
            'share_contact_info' => 'required|boolean',
            'contact_info' => 'nullable|string|max:255',
            'other_requirements' => 'nullable|string|max:1000',
        ], [
            'expected_completion_date.required' => '请选择期望完成时间',
            'expected_completion_date.date' => '请输入有效的日期',
            'expected_completion_date.after_or_equal' => '完成时间不能早于今天',
            'account_opening_area.required' => '请选择开户区域',
            'share_contact_info.required' => '请选择是否公开联系方式',
        ]);

        // 确保用户已登录
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', '请先登录后再下单');
        }

        // 确保服务是活跃的
        if (!$service->is_active) {
            return back()->withErrors(['service' => '该服务已停用']);
        }

        // 创建订单
        $order = Order::create([
            'user_id' => Auth::id(),
            'teacher_id' => $service->user_id,
            'service_id' => $service->id,
            'status' => 'pending',
            'expected_completion_date' => $validated['expected_completion_date'],
            'account_opening_area' => $validated['account_opening_area'],
            'share_contact_info' => $validated['share_contact_info'],
            'other_requirements' => $validated['other_requirements'],
            'order_created_at' => now(),
            'payment_amount' => $service->service_amount,
            'is_paid' => false,
            'paid_amount' => 0,
        ]);

        // 更新用户发起订单数
        Auth::user()->increment('initiated_orders');

        // 返回成功页面或重定向
        return redirect()->route('orders.show', $order)->with('success', '订单提交成功！');
    }

    /**
     * 显示老师的订单列表
     */
    public function teacherOrders()
    {
        // 确保用户是老师
        if (Auth::user()->user_type !== 'teacher') {
            abort(403, '只有老师可以查看服务订单');
        }

        // 获取老师接收的所有订单
        $orders = Order::where('teacher_id', Auth::id())
            ->with(['user', 'service'])
            ->orderByDesc('created_at')
            ->get();

        return view('orders.teacher-orders', compact('orders'));
    }

    /**
     * 显示用户发起的订单列表
     */
    public function customerOrders()
    {
        // 获取当前用户发起的所有订单
        $orders = Order::where('user_id', Auth::id())
            ->with(['teacher', 'service'])
            ->orderByDesc('created_at')
            ->get();

        return view('orders.customer-orders', compact('orders'));
    }

    /**
     * 显示订单详情
     */
    public function show(Order $order)
    {
        // 确保用户只能查看自己的订单或者是订单对应的老师
        if ($order->user_id !== Auth::id() && $order->teacher_id !== Auth::id()) {
            abort(403, '无权访问该订单');
        }

        // 加载相关数据
        $order->load(['user', 'teacher', 'service']);

        // 订单状态流程配置
        $statusFlow = [
            'pending' => ['step' => 1, 'label' => '等待老师接受', 'color' => 'yellow'],
            'accepted' => ['step' => 2, 'label' => '老师已接受', 'color' => 'blue'],
            'paid' => ['step' => 3, 'label' => '已支付', 'color' => 'green'],
            'in_progress' => ['step' => 4, 'label' => '服务进行中', 'color' => 'blue'],
            'completed' => ['step' => 5, 'label' => '等待用户确认', 'color' => 'yellow'],
            'confirmed' => ['step' => 6, 'label' => '已确认完成', 'color' => 'green'],
            'cancelled' => ['step' => 0, 'label' => '已取消', 'color' => 'red'],
            'rejected' => ['step' => 0, 'label' => '已拒绝', 'color' => 'red'],
        ];

        $currentStatus = $statusFlow[$order->status] ?? ['step' => 1, 'label' => '未知状态', 'color' => 'gray'];

        return view('orders.show', compact('order', 'currentStatus'));
    }

    /**
     * 老师接受订单
     */
    public function accept(Request $request, Order $order)
    {
        // 确保只有老师可以接受订单
        if (Auth::user()->user_type !== 'teacher') {
            abort(403, '只有老师可以接受订单');
        }

        // 确保是订单对应的老师
        if ($order->teacher_id !== Auth::id()) {
            abort(403, '无权操作该订单');
        }

        // 确保订单状态是pending
        if ($order->status !== 'pending') {
            return back()->with('error', '该订单状态不允许接受');
        }

        // 验证表单数据
        $validated = $request->validate([
            'teacher_recommended_bank' => 'nullable|string|max:255',
            'estimated_online_appointment_time' => 'nullable|date',
            'teacher_message' => 'nullable|string|max:1000',
        ]);

        // 更新订单状态和老师信息
        $order->update([
            'status' => 'accepted',
            'teacher_recommended_bank' => $validated['teacher_recommended_bank'],
            'estimated_online_appointment_time' => $validated['estimated_online_appointment_time'],
            'teacher_message' => $validated['teacher_message'],
            'accepted_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)->with('success', '订单已接受，用户将收到通知');
    }

    /**
     * 老师拒绝订单
     */
    public function reject(Request $request, Order $order)
    {
        // 确保只有老师可以拒绝订单
        if (Auth::user()->user_type !== 'teacher') {
            abort(403, '只有老师可以拒绝订单');
        }

        // 确保是订单对应的老师
        if ($order->teacher_id !== Auth::id()) {
            abort(403, '无权操作该订单');
        }

        // 确保订单状态是pending
        if ($order->status !== 'pending') {
            return back()->with('error', '该订单状态不允许拒绝');
        }

        // 验证表单数据
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => '请输入拒绝理由',
            'rejection_reason.max' => '拒绝理由不能超过1000字符',
        ]);

        // 更新订单状态和拒绝原因
        $order->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
        ]);

        return redirect()->route('orders.teacher')->with('success', '订单已拒绝，用户将收到通知');
    }

    /**
     * 老师取消订单
     */
    public function cancel(Order $order)
    {
        // 确保只有老师可以取消订单
        if (Auth::user()->user_type !== 'teacher') {
            abort(403, '只有老师可以取消订单');
        }

        // 确保是订单对应的老师
        if ($order->teacher_id !== Auth::id()) {
            abort(403, '无权操作该订单');
        }

        // 确保订单状态是accepted
        if ($order->status !== 'accepted') {
            return back()->with('error', '该订单状态不允许取消');
        }

        // 更新订单状态
        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('orders.teacher')->with('success', '订单已取消');
    }

    /**
     * 老师完成订单
     */
    public function complete(Order $order)
    {
        // 确保只有老师可以完成订单
        if (Auth::user()->user_type !== 'teacher') {
            abort(403, '只有老师可以完成订单');
        }

        // 确保是订单对应的老师
        if ($order->teacher_id !== Auth::id()) {
            abort(403, '无权操作该订单');
        }

        // 确保订单状态是accepted
        if ($order->status !== 'accepted') {
            return back()->with('error', '该订单状态不允许完成');
        }

        // 更新订单状态
        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // 更新老师完成订单数
        Auth::user()->increment('completed_orders');

        // 更新服务完成订单数
        if ($order->service) {
            $order->service->increment('completed_orders_count');
        }

        return redirect()->route('orders.show', $order)->with('success', '订单已完成，等待用户确认');
    }

    /**
     * 用户确认订单完成
     */
    public function confirm(Order $order)
    {
        // 确保只有用户可以确认订单
        if ($order->user_id !== Auth::id()) {
            abort(403, '无权操作该订单');
        }

        // 确保订单状态是completed
        if ($order->status !== 'completed') {
            return back()->with('error', '该订单状态不允许确认');
        }

        // 更新订单状态
        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)->with('success', '订单已确认完成，感谢您的使用！');
    }
}
