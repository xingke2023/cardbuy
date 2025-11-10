<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's invitations page.
     */
    public function invitations(Request $request): View
    {
        $user = $request->user();
        
        // 获取用户邀请的所有用户
        $invitedUsers = $user->invitees()->orderByDesc('created_at')->get();
        
        // 计算邀请统计
        $totalInvited = $invitedUsers->count();
        $totalCommission = 888; // 这里应该根据实际业务逻辑计算
        
        return view('profile.invitations', [
            'user' => $user,
            'invitedUsers' => $invitedUsers,
            'totalInvited' => $totalInvited,
            'totalCommission' => $totalCommission,
        ]);
    }

    /**
     * Display the help page.
     */
    public function help(Request $request): View
    {
        return view('help', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's wallet page.
     */
    public function wallet(Request $request): View
    {
        $user = $request->user();
        $filter = $request->get('filter', 'all');
        
        // 模拟钱包数据
        $totalBalance = 1123;
        $availableBalance = 1000;
        
        // 模拟交易记录
        $transactions = collect([
            ['type' => 'income', 'category' => 'service', 'description' => '服务收益', 'amount' => 500, 'created_at' => '2024-09-10 23:22:01'],
            ['type' => 'income', 'category' => 'referral', 'description' => '好友分成', 'amount' => 50, 'created_at' => '2024-09-10 23:21:01'],
            ['type' => 'income', 'category' => 'service', 'description' => '服务收益', 'amount' => 500, 'created_at' => '2024-09-10 23:20:01'],
            ['type' => 'expense', 'category' => 'withdrawal', 'description' => '提现', 'amount' => 500, 'created_at' => '2024-09-10 23:19:01'],
            ['type' => 'income', 'category' => 'service', 'description' => '服务收益', 'amount' => 500, 'created_at' => '2024-09-10 23:18:01'],
            ['type' => 'income', 'category' => 'service', 'description' => '服务收益', 'amount' => 500, 'created_at' => '2024-09-10 23:17:01'],
            ['type' => 'expense', 'category' => 'withdrawal', 'description' => '提现', 'amount' => 500, 'created_at' => '2024-09-10 23:16:01'],
            ['type' => 'income', 'category' => 'service', 'description' => '服务收益', 'amount' => 500, 'created_at' => '2024-09-10 23:15:01'],
            ['type' => 'income', 'category' => 'service', 'description' => '服务收益', 'amount' => 500, 'created_at' => '2024-09-10 23:14:01'],
        ]);

        // 根据筛选条件过滤交易记录
        $filteredTransactions = $this->filterTransactions($transactions, $filter);
        
        return view('profile.wallet', [
            'user' => $user,
            'totalBalance' => $totalBalance,
            'availableBalance' => $availableBalance,
            'transactions' => $filteredTransactions,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * Filter transactions based on the given filter.
     */
    private function filterTransactions($transactions, $filter)
    {
        switch ($filter) {
            case 'income':
                return $transactions->where('type', 'income');
            case 'income_service':
                return $transactions->where('type', 'income')->where('category', 'service');
            case 'income_referral':
                return $transactions->where('type', 'income')->where('category', 'referral');
            case 'expense':
                return $transactions->where('type', 'expense');
            default:
                return $transactions;
        }
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
