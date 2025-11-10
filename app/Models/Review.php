<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 评价模型
 */
class Review extends Model
{
    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'user_id',    // 评价用户ID
        'order_id',   // 订单ID
        'teacher_id', // 被评价的老师ID
        'rating',     // 评分(1-5分)
        'comment',    // 评价内容
    ];

    /**
     * 评价用户
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 评价的订单
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 被评价的老师
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
