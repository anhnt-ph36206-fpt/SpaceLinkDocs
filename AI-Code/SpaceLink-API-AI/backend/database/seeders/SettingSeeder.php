<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key_name' => 'site_name', 'value' => 'SpaceLink', 'type' => 'string', 'group_name' => 'general', 'description' => 'Tên website', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'site_logo', 'value' => '/images/logo.png', 'type' => 'string', 'group_name' => 'general', 'description' => 'Logo website', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'site_email', 'value' => 'contact@spacelink.com', 'type' => 'string', 'group_name' => 'general', 'description' => 'Email liên hệ', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'site_phone', 'value' => '1900 1234', 'type' => 'string', 'group_name' => 'general', 'description' => 'Hotline', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'site_address', 'value' => 'Hà Nội, Việt Nam', 'type' => 'string', 'group_name' => 'general', 'description' => 'Địa chỉ', 'is_public' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'shipping_fee', 'value' => '30000', 'type' => 'number', 'group_name' => 'shipping', 'description' => 'Phí vận chuyển mặc định', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'free_shipping_amount', 'value' => '500000', 'type' => 'number', 'group_name' => 'shipping', 'description' => 'Miễn phí ship khi đơn hàng trên', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'vnpay_enabled', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'payment', 'description' => 'Bật thanh toán VNPAY', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'momo_enabled', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'payment', 'description' => 'Bật thanh toán MOMO', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('settings')->insert($settings);
    }
}
