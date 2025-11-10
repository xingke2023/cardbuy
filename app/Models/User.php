<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     * 可批量赋值的属性
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',                    // 姓名
        'gender',                  // 性别
        'nickname',                // 昵称
        'organization',            // 任职机构
        'city',                    // 常驻城市
        'avatar',                  // 头像
        'email',                   // 邮箱
        'password',                // 密码
        'phone',                   // 联系电话
        'wechat',                  // 微信号
        'whatsapp',                // WhatsApp号
        'user_type',               // 用户类型
        'is_verified_teacher',     // 是否已经认证老师
        'completed_orders',        // 完成订单数
        'initiated_orders',        // 发起订单数
        'current_rating',          // 目前的评分
        'specialties',             // 擅长
        'personal_introduction',   // 个人介绍
        'serviceable_banks',       // 可以服务的银行
        'teacher_reviews',         // 本老师用户的评价
        'inviter_id',              // 邀请人ID
        'address',                 // 地址
    ];

    /**
     * The attributes that should be hidden for serialization.
     * 序列化时应隐藏的属性
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // 密码
        'remember_token',  // 记住登录令牌
    ];

    /**
     * Get the attributes that should be cast.
     * 获取应该转换的属性
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',    // 邮箱验证时间
            'password' => 'hashed',               // 密码哈希
            'is_verified_teacher' => 'boolean',   // 是否已认证老师
            'current_rating' => 'decimal:2',      // 当前评分
        ];
    }

    /**
     * 用户提供的服务
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * 作为客户的订单
     */
    public function customerOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * 作为老师的订单
     */
    public function teacherOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'teacher_id');
    }

    /**
     * 用户发出的评价
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * 作为老师收到的评价
     */
    public function teacherReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'teacher_id');
    }

    /**
     * 邀请人
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    /**
     * 被邀请的用户
     */
    public function invitees(): HasMany
    {
        return $this->hasMany(User::class, 'inviter_id');
    }

    /**
     * 是否为老师
     */
    public function isTeacher(): bool
    {
        return $this->user_type === 'teacher';
    }

    /**
     * 是否为客户
     */
    public function isCustomer(): bool
    {
        return $this->user_type === 'customer';
    }

    /**
     * 是否为管理员
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }
}
