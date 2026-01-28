<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parent Categories
        $parents = [
            ['id' => 1, 'parent_id' => null, 'name' => 'Điện thoại', 'slug' => 'dien-thoai', 'is_active' => true, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'parent_id' => null, 'name' => 'Máy tính bảng', 'slug' => 'may-tinh-bang', 'is_active' => true, 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'parent_id' => null, 'name' => 'Laptop', 'slug' => 'laptop', 'is_active' => true, 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'parent_id' => null, 'name' => 'Phụ kiện', 'slug' => 'phu-kien', 'is_active' => true, 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($parents);

        // Child Categories
        $children = [
            ['id' => 5, 'parent_id' => 1, 'name' => 'iPhone', 'slug' => 'iphone', 'is_active' => true, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'parent_id' => 1, 'name' => 'Samsung Galaxy', 'slug' => 'samsung-galaxy', 'is_active' => true, 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'parent_id' => 2, 'name' => 'iPad', 'slug' => 'ipad', 'is_active' => true, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'parent_id' => 3, 'name' => 'MacBook', 'slug' => 'macbook', 'is_active' => true, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'parent_id' => 4, 'name' => 'Tai nghe', 'slug' => 'tai-nghe', 'is_active' => true, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'parent_id' => 4, 'name' => 'Sạc & Cáp', 'slug' => 'sac-cap', 'is_active' => true, 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($children);
    }
}
