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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->comment('订单ID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('订单用户ID');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade')->comment('老师ID');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'paid', 'in_progress', 'completed', 'cancelled'])->default('pending')->comment('订单的状态');
            $table->date('expected_completion_date')->nullable()->comment('期望完成的时间');
            $table->string('account_opening_area')->nullable()->comment('开户区域');
            $table->boolean('share_contact_info')->default(false)->comment('是否向老师公开您的联系方式');
            $table->text('other_requirements')->nullable()->comment('其他需求');
            $table->timestamp('order_created_at')->useCurrent()->comment('订单创建时间');
            $table->decimal('payment_amount', 10, 2)->default(0)->comment('支付金额');
            $table->text('rejection_reason')->nullable()->comment('订单拒绝理由');
            $table->string('teacher_recommended_bank')->nullable()->comment('老师推荐银行');
            $table->datetime('estimated_online_appointment_time')->nullable()->comment('预计线上预约时间');
            $table->text('teacher_message')->nullable()->comment('老师留言');
            $table->boolean('is_paid')->default(false)->comment('订单是否已付款');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('已付款金额');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
