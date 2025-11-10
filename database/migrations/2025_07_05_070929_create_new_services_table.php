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
        Schema::create('services', function (Blueprint $table) {
            $table->id()->comment('服务项目ID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('用户ID');
            $table->string('service_name')->comment('服务项目名称');
            $table->decimal('service_amount', 10, 2)->comment('服务项目的金额');
            $table->integer('completed_orders_count')->default(0)->comment('本项目已经完成了多少订单');
            $table->text('service_details')->comment('服务详情');
            $table->enum('service_type', ['credit_card', 'debit_card', 'business_card', 'other'])->comment('服务类型');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
