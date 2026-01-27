<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed dữ liệu mẫu cho bảng categories (đa cấp)
     */
    public function run(): void
    {
        // ===========================
        // DANH MỤC CHA (Level 0)
        // ===========================
        $parents = [
            [
                'name' => 'Điện thoại',
                'icon' => 'fa-mobile-alt',
                'description' => 'Điện thoại thông minh các hãng',
                'order' => 1,
            ],
            [
                'name' => 'Máy tính bảng',
                'icon' => 'fa-tablet-alt',
                'description' => 'Tablet, iPad các loại',
                'order' => 2,
            ],
            [
                'name' => 'Laptop',
                'icon' => 'fa-laptop',
                'description' => 'Máy tính xách tay',
                'order' => 3,
            ],
            [
                'name' => 'Phụ kiện',
                'icon' => 'fa-headphones',
                'description' => 'Phụ kiện điện thoại, laptop',
                'order' => 4,
            ],
            [
                'name' => 'Đồng hồ thông minh',
                'icon' => 'fa-clock',
                'description' => 'Smartwatch các hãng',
                'order' => 5,
            ],
        ];

        $parentCategories = [];
        foreach ($parents as $parent) {
            $category = Category::create([
                'parent_id' => null,
                'name' => $parent['name'],
                'slug' => Str::slug($parent['name']),
                'icon' => $parent['icon'],
                'description' => $parent['description'],
                'display_order' => $parent['order'],
                'is_active' => true,
            ]);
            $parentCategories[$parent['name']] = $category->id;
        }

        // ===========================
        // DANH MỤC CON (Level 1)
        // ===========================
        $children = [
            // === Điện thoại ===
            ['parent' => 'Điện thoại', 'name' => 'iPhone', 'order' => 1],
            ['parent' => 'Điện thoại', 'name' => 'Samsung Galaxy', 'order' => 2],
            ['parent' => 'Điện thoại', 'name' => 'Xiaomi', 'order' => 3],
            ['parent' => 'Điện thoại', 'name' => 'OPPO', 'order' => 4],
            ['parent' => 'Điện thoại', 'name' => 'Vivo', 'order' => 5],
            ['parent' => 'Điện thoại', 'name' => 'Realme', 'order' => 6],

            // === Máy tính bảng ===
            ['parent' => 'Máy tính bảng', 'name' => 'iPad', 'order' => 1],
            ['parent' => 'Máy tính bảng', 'name' => 'Samsung Tab', 'order' => 2],
            ['parent' => 'Máy tính bảng', 'name' => 'Xiaomi Pad', 'order' => 3],

            // === Laptop ===
            ['parent' => 'Laptop', 'name' => 'MacBook', 'order' => 1],
            ['parent' => 'Laptop', 'name' => 'Dell', 'order' => 2],
            ['parent' => 'Laptop', 'name' => 'HP', 'order' => 3],
            ['parent' => 'Laptop', 'name' => 'Asus', 'order' => 4],
            ['parent' => 'Laptop', 'name' => 'Lenovo', 'order' => 5],

            // === Phụ kiện ===
            ['parent' => 'Phụ kiện', 'name' => 'Tai nghe', 'order' => 1],
            ['parent' => 'Phụ kiện', 'name' => 'Sạc & Cáp', 'order' => 2],
            ['parent' => 'Phụ kiện', 'name' => 'Ốp lưng', 'order' => 3],
            ['parent' => 'Phụ kiện', 'name' => 'Cường lực', 'order' => 4],
            ['parent' => 'Phụ kiện', 'name' => 'Pin dự phòng', 'order' => 5],
            ['parent' => 'Phụ kiện', 'name' => 'Bàn phím', 'order' => 6],
            ['parent' => 'Phụ kiện', 'name' => 'Chuột', 'order' => 7],

            // === Đồng hồ thông minh ===
            ['parent' => 'Đồng hồ thông minh', 'name' => 'Apple Watch', 'order' => 1],
            ['parent' => 'Đồng hồ thông minh', 'name' => 'Samsung Galaxy Watch', 'order' => 2],
            ['parent' => 'Đồng hồ thông minh', 'name' => 'Xiaomi Watch', 'order' => 3],
        ];

        $childCategories = [];
        foreach ($children as $child) {
            $parentId = $parentCategories[$child['parent']] ?? null;
            
            $category = Category::create([
                'parent_id' => $parentId,
                'name' => $child['name'],
                'slug' => Str::slug($child['name']),
                'display_order' => $child['order'],
                'is_active' => true,
            ]);
            $childCategories[$child['name']] = $category->id;
        }

        // ===========================
        // DANH MỤC CHÁU (Level 2) - Tùy chọn
        // ===========================
        $grandchildren = [
            ['parent' => 'iPhone', 'name' => 'iPhone 16 Series', 'order' => 1],
            ['parent' => 'iPhone', 'name' => 'iPhone 15 Series', 'order' => 2],
            ['parent' => 'iPhone', 'name' => 'iPhone 14 Series', 'order' => 3],
            ['parent' => 'Samsung Galaxy', 'name' => 'Galaxy S Series', 'order' => 1],
            ['parent' => 'Samsung Galaxy', 'name' => 'Galaxy A Series', 'order' => 2],
            ['parent' => 'Samsung Galaxy', 'name' => 'Galaxy Z Series', 'order' => 3],
        ];

        foreach ($grandchildren as $grandchild) {
            $parentId = $childCategories[$grandchild['parent']] ?? null;
            
            if ($parentId) {
                Category::create([
                    'parent_id' => $parentId,
                    'name' => $grandchild['name'],
                    'slug' => Str::slug($grandchild['name']),
                    'display_order' => $grandchild['order'],
                    'is_active' => true,
                ]);
            }
        }

        $totalCategories = Category::count();
        $this->command->info("✅ Đã tạo {$totalCategories} danh mục (3 cấp)");
    }
}
