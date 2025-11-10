<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 用户表字段添加
            $table->string('wechat')->nullable()->after('phone')->comment('微信号');
            $table->string('whatsapp')->nullable()->after('wechat')->comment('WhatsApp号');
            $table->boolean('is_verified_teacher')->default(false)->after('user_type')->comment('是否已经认证老师');
            $table->integer('completed_orders')->default(0)->after('is_verified_teacher')->comment('完成订单数');
            $table->integer('initiated_orders')->default(0)->after('completed_orders')->comment('发起订单数');
            $table->decimal('current_rating', 3, 2)->default(0)->after('initiated_orders')->comment('目前的评分');
            $table->text('specialties')->nullable()->after('current_rating')->comment('擅长');
            $table->text('personal_introduction')->nullable()->after('specialties')->comment('个人介绍');
            $table->text('serviceable_banks')->nullable()->after('personal_introduction')->comment('可以服务的银行');
            $table->text('teacher_reviews')->nullable()->after('serviceable_banks')->comment('本老师用户的评价');
            $table->foreignId('inviter_id')->nullable()->constrained('users')->onDelete('set null')->after('teacher_reviews')->comment('邀请人ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'wechat', 'whatsapp', 'is_verified_teacher',
                'completed_orders', 'initiated_orders', 'current_rating',
                'specialties', 'personal_introduction', 'serviceable_banks',
                'teacher_reviews', 'inviter_id'
            ]);
        });
    }
};
