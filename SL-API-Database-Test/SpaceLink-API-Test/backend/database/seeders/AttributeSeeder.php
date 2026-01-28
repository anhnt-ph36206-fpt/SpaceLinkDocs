<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Attribute Groups
        $groups = [
            ['id' => 1, 'name' => 'color', 'display_name' => 'Màu sắc', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'storage', 'display_name' => 'Dung lượng', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'ram', 'display_name' => 'RAM', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('attribute_groups')->insert($groups);

        // 2. Attributes
        $attributes = [
            // Colors
            ['attribute_group_id' => 1, 'value' => 'Đen', 'color_code' => '#000000', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 1, 'value' => 'Trắng', 'color_code' => '#FFFFFF', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 1, 'value' => 'Xanh Dương', 'color_code' => '#0066CC', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 1, 'value' => 'Hồng', 'color_code' => '#FF69B4', 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 1, 'value' => 'Vàng', 'color_code' => '#FFD700', 'display_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            // Storage
            ['attribute_group_id' => 2, 'value' => '64GB', 'color_code' => null, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 2, 'value' => '128GB', 'color_code' => null, 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 2, 'value' => '256GB', 'color_code' => null, 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 2, 'value' => '512GB', 'color_code' => null, 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 2, 'value' => '1TB', 'color_code' => null, 'display_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            // RAM
            ['attribute_group_id' => 3, 'value' => '4GB', 'color_code' => null, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 3, 'value' => '6GB', 'color_code' => null, 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 3, 'value' => '8GB', 'color_code' => null, 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 3, 'value' => '12GB', 'color_code' => null, 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_group_id' => 3, 'value' => '16GB', 'color_code' => null, 'display_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('attributes')->insert($attributes);
    }
}
