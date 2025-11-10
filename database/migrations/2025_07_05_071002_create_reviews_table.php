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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id()->comment('评价ID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('评价用户ID');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->comment('订单ID');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade')->comment('被评价的老师ID');
            $table->integer('rating')->min(1)->max(5)->comment('评分(1-5分)');
            $table->text('comment')->nullable()->comment('评价内容');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
