<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 订单模型
 */
class Order extends Model
{
    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'user_id',                            // 订单用户ID
        'teacher_id',                         // 老师ID
        'service_id',                         // 服务ID
        'status',                             // 订单的状态
        'expected_completion_date',           // 期望完成的时间
        'account_opening_area',               // 开户区域
        'share_contact_info',                 // 是否向老师公开您的联系方式
        'other_requirements',                 // 其他需求
        'order_created_at',                   // 订单创建时间
        'payment_amount',                     // 支付金额
        'rejection_reason',                   // 订单拒绝理由
        'teacher_recommended_bank',           // 老师推荐银行
        'estimated_online_appointment_time',  // 预计线上预约时间
        'teacher_message',                    // 老师留言
        'is_paid',                            // 订单是否已付款
        'paid_amount',                        // 已付款金额
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'expected_completion_date' => 'date',            // 期望完成日期
        'share_contact_info' => 'boolean',               // 是否分享联系信息
        'order_created_at' => 'datetime',                // 订单创建时间
        'payment_amount' => 'decimal:2',                 // 支付金额
        'estimated_online_appointment_time' => 'datetime', // 预计线上预约时间
        'is_paid' => 'boolean',                          // 是否已付款
        'paid_amount' => 'decimal:2',                    // 已付款金额
    ];

    /**
     * 订单客户
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 订单老师
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * 订单服务
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * 订单评价
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
