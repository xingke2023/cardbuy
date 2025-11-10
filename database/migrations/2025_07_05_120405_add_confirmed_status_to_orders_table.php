<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 修改status字段的ENUM值，添加confirmed状态
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'paid', 'in_progress', 'completed', 'cancelled', 'confirmed') DEFAULT 'pending' COMMENT '订单的状态'");
        
        // 添加确认时间字段
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->comment('订单确认时间');
            $table->timestamp('accepted_at')->nullable()->comment('订单接受时间');
            $table->timestamp('rejected_at')->nullable()->comment('订单拒绝时间');
            $table->timestamp('cancelled_at')->nullable()->comment('订单取消时间');
            $table->timestamp('completed_at')->nullable()->comment('订单完成时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 移除新添加的时间字段
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['confirmed_at', 'accepted_at', 'rejected_at', 'cancelled_at', 'completed_at']);
        });
        
        // 恢复原来的ENUM值
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'paid', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending' COMMENT '订单的状态'");
    }
};
