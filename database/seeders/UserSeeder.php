<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create customer user
        $customer = User::create([
            'name' => '张三',
            'gender' => 'male',
            'nickname' => '张先生',
            'organization' => '阿里巴巴',
            'city' => '杭州',
            'email' => 'customer@cardbase.com',
            'password' => bcrypt('password'),
            'phone' => '13800138000',
            'wechat' => 'zhangsan_wx',
            'whatsapp' => '+86138001380001',
            'user_type' => 'customer',
            'is_verified_teacher' => false,
            'completed_orders' => 0,
            'initiated_orders' => 5,
            'current_rating' => 0,
            'address' => '浙江省杭州市西湖区'
        ]);

        // Create teacher users
        $teacher1 = User::create([
            'name' => '李老师',
            'gender' => 'female',
            'nickname' => '李金融',
            'organization' => '银行卡办理专家',
            'city' => '北京',
            'email' => 'teacher1@cardbase.com',
            'password' => bcrypt('password'),
            'phone' => '13900139000',
            'wechat' => 'li_finance',
            'whatsapp' => '+86139001390001',
            'user_type' => 'teacher',
            'is_verified_teacher' => true,
            'completed_orders' => 150,
            'initiated_orders' => 0,
            'current_rating' => 4.8,
            'specialties' => '信用卡申请,储蓄卡办理,企业账户开户',
            'personal_introduction' => '拥有10年银行卡办理经验，成功率高达95%，为客户提供专业的银行卡申请服务。',
            'serviceable_banks' => '工商银行,建设银行,农业银行,中国银行,招商银行,交通银行',
            'teacher_reviews' => '专业可靠，服务态度好，办理速度快',
            'address' => '北京市朝阳区国贸'
        ]);

        $teacher2 = User::create([
            'name' => '王专家',
            'gender' => 'male',
            'nickname' => '王银行',
            'organization' => '快速银行卡服务',
            'city' => '上海',
            'email' => 'teacher2@cardbase.com',
            'password' => bcrypt('password'),
            'phone' => '13700137000',
            'wechat' => 'wang_bank',
            'whatsapp' => '+86137001370001',
            'user_type' => 'teacher',
            'is_verified_teacher' => true,
            'completed_orders' => 80,
            'initiated_orders' => 0,
            'current_rating' => 4.5,
            'specialties' => '企业银行卡,高端信用卡,国际卡',
            'personal_introduction' => '专注于企业银行卡和高端信用卡申请，拥有丰富的银行资源和经验。',
            'serviceable_banks' => '招商银行,浦发银行,兴业银行,民生银行,中信银行',
            'teacher_reviews' => '专业高效，成功率高',
            'address' => '上海市浦东新区陆家嘴',
            'inviter_id' => $teacher1->id
        ]);

        // Create services
        Service::create([
            'user_id' => $teacher1->id,
            'service_name' => '工商银行信用卡申请',
            'service_amount' => 299.00,
            'completed_orders_count' => 45,
            'service_details' => '专业办理工商银行信用卡，成功率高，审批快速。包含材料准备、申请指导、进度跟踪等全程服务。',
            'service_type' => 'credit_card',
            'is_active' => true
        ]);

        Service::create([
            'user_id' => $teacher1->id,
            'service_name' => '招商银行储蓄卡办理',
            'service_amount' => 199.00,
            'completed_orders_count' => 60,
            'service_details' => '快速办理招商银行储蓄卡，当日申请，次日取卡。提供开户指导和使用培训。',
            'service_type' => 'debit_card',
            'is_active' => true
        ]);

        Service::create([
            'user_id' => $teacher2->id,
            'service_name' => '建设银行企业账户开户',
            'service_amount' => 499.00,
            'completed_orders_count' => 25,
            'service_details' => '专业办理建设银行企业账户开户，包含基本户、一般户、专用户等。提供全程代办服务。',
            'service_type' => 'business_card',
            'is_active' => true
        ]);

        Service::create([
            'user_id' => $teacher2->id,
            'service_name' => '浦发银行信用卡申请',
            'service_amount' => 399.00,
            'completed_orders_count' => 30,
            'service_details' => '办理浦发银行各类信用卡，包含普卡、金卡、白金卡等。根据客户资质推荐最适合的卡种。',
            'service_type' => 'credit_card',
            'is_active' => true
        ]);
    }
}
