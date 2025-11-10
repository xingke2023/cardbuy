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
        Schema::table('services', function (Blueprint $table) {
            // 修改service_type字段从enum改为varchar
            $table->string('service_type')->comment('服务类型')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // 回滚时改回enum类型
            $table->enum('service_type', ['credit_card', 'debit_card', 'business_card', 'other'])->comment('服务类型')->change();
        });
    }
};
