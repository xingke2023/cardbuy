<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 服务项目模型
 */
class Service extends Model
{
    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'user_id',                 // 用户ID
        'service_name',            // 服务项目名称
        'service_amount',          // 服务项目的金额
        'completed_orders_count',  // 本项目已经完成了多少订单
        'service_details',         // 服务详情
        'service_type',            // 服务类型
        'is_active',               // 是否启用
    ];

    /**
     * 属性转换
     */
    protected $casts = [
        'service_amount' => 'decimal:2',  // 服务金额
        'is_active' => 'boolean',         // 是否启用
    ];

    /**
     * 提供服务的用户(老师)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 此服务的订单
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
