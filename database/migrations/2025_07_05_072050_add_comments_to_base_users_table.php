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
        // 为已存在的用户表字段添加中文注释
        DB::statement("ALTER TABLE users MODIFY COLUMN id bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID'");
        DB::statement("ALTER TABLE users MODIFY COLUMN name varchar(255) NOT NULL COMMENT '姓名'");
        DB::statement("ALTER TABLE users MODIFY COLUMN gender enum('male','female','other') NULL COMMENT '性别'");
        DB::statement("ALTER TABLE users MODIFY COLUMN nickname varchar(255) NULL COMMENT '昵称'");
        DB::statement("ALTER TABLE users MODIFY COLUMN organization varchar(255) NULL COMMENT '任职机构'");
        DB::statement("ALTER TABLE users MODIFY COLUMN city varchar(255) NULL COMMENT '常驻城市'");
        DB::statement("ALTER TABLE users MODIFY COLUMN avatar varchar(255) NULL COMMENT '头像'");
        DB::statement("ALTER TABLE users MODIFY COLUMN email varchar(255) NOT NULL COMMENT '邮箱'");
        DB::statement("ALTER TABLE users MODIFY COLUMN email_verified_at timestamp NULL COMMENT '邮箱验证时间'");
        DB::statement("ALTER TABLE users MODIFY COLUMN password varchar(255) NOT NULL COMMENT '密码'");
        DB::statement("ALTER TABLE users MODIFY COLUMN phone varchar(255) NULL COMMENT '联系电话'");
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type enum('customer','teacher','admin') NOT NULL DEFAULT 'customer' COMMENT '用户类型'");
        DB::statement("ALTER TABLE users MODIFY COLUMN address text NULL COMMENT '地址'");
        DB::statement("ALTER TABLE users MODIFY COLUMN remember_token varchar(100) NULL COMMENT '记住登录令牌'");
        DB::statement("ALTER TABLE users MODIFY COLUMN created_at timestamp NULL COMMENT '创建时间'");
        DB::statement("ALTER TABLE users MODIFY COLUMN updated_at timestamp NULL COMMENT '更新时间'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 移除注释的操作，实际项目中通常不需要down方法来移除注释
        // 这里留空即可
    }
};
