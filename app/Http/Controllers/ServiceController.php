<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * 服务控制器
 */
class ServiceController extends Controller
{
    /**
     * 显示我的服务页面
     */
    public function myServices(Request $request): View
    {
        // 获取当前用户的所有服务
        $services = Auth::user()->services()->orderBy('created_at', 'desc')->get();
        
        return view('services.my-services', compact('services'));
    }

    /**
     * 显示创建服务页面
     */
    public function create(): View
    {
        return view('services.create');
    }

    /**
     * 存储新服务
     */
    public function store(Request $request)
    {
        // 验证请求数据
        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'service_name' => 'required|string|max:255',
            'service_details' => 'required|string',
            'service_amount' => 'required|numeric|min:0',
        ], [
            'service_type.required' => '请选择服务类型',
            'service_name.required' => '请输入服务标题',
            'service_details.required' => '请输入服务详情',
            'service_amount.required' => '请输入服务价格',
            'service_amount.numeric' => '服务价格必须是数字',
            'service_amount.min' => '服务价格不能小于0',
        ]);

        // 确保用户是老师
        if (Auth::user()->user_type !== 'teacher') {
            return redirect()->route('profile.show')->with('error', '只有老师可以创建服务');
        }

        // 创建服务
        Auth::user()->services()->create([
            'service_type' => $validated['service_type'],
            'service_name' => $validated['service_name'],
            'service_details' => $validated['service_details'],
            'service_amount' => $validated['service_amount'],
            'is_active' => true,
            'completed_orders_count' => 0,
        ]);

        return redirect()->route('services.my')->with('success', '服务创建成功！');
    }

    /**
     * 显示服务详情页面
     */
    public function show(\App\Models\Service $service): View
    {
        // 确保只有服务拥有者可以查看详情
        if ($service->user_id !== Auth::id()) {
            abort(403, '无权访问该服务');
        }

        // 加载服务拥有者信息
        $service->load('user');

        return view('services.show', compact('service'));
    }

    /**
     * 显示编辑服务页面
     */
    public function edit(\App\Models\Service $service): View
    {
        // 确保只有服务拥有者可以编辑
        if ($service->user_id !== Auth::id()) {
            abort(403, '无权编辑该服务');
        }

        return view('services.edit', compact('service'));
    }

    /**
     * 更新服务
     */
    public function update(Request $request, \App\Models\Service $service)
    {
        // 确保只有服务拥有者可以更新
        if ($service->user_id !== Auth::id()) {
            abort(403, '无权更新该服务');
        }

        // 验证请求数据
        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'service_name' => 'required|string|max:255',
            'service_details' => 'required|string',
            'service_amount' => 'required|numeric|min:0',
        ], [
            'service_type.required' => '请选择服务类型',
            'service_name.required' => '请输入服务标题',
            'service_details.required' => '请输入服务详情',
            'service_amount.required' => '请输入服务价格',
            'service_amount.numeric' => '服务价格必须是数字',
            'service_amount.min' => '服务价格不能小于0',
        ]);

        // 更新服务
        $service->update($validated);

        return redirect()->route('services.show', $service)->with('success', '服务更新成功！');
    }

    /**
     * 删除服务
     */
    public function destroy(\App\Models\Service $service)
    {
        // 确保只有服务拥有者可以删除
        if ($service->user_id !== Auth::id()) {
            abort(403, '无权删除该服务');
        }

        // 检查是否有订单关联此服务
        $orderCount = $service->orders()->count();
        if ($orderCount > 0) {
            return back()->with('error', '该服务有关联订单，无法删除');
        }

        // 删除服务
        $service->delete();

        return redirect()->route('services.my')->with('success', '服务删除成功！');
    }

    /**
     * 切换服务状态
     */
    public function toggleStatus(\App\Models\Service $service)
    {
        // 确保只有服务拥有者可以切换状态
        if ($service->user_id !== Auth::id()) {
            abort(403, '无权操作该服务');
        }

        // 切换服务状态
        $service->update([
            'is_active' => !$service->is_active
        ]);

        $status = $service->is_active ? '开启' : '关闭';
        return back()->with('success', "服务已{$status}");
    }
}
