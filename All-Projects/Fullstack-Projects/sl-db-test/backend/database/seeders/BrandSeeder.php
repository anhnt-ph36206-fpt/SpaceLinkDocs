<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Seed dữ liệu mẫu cho bảng brands
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'description' => 'Thương hiệu công nghệ hàng đầu thế giới với các sản phẩm iPhone, iPad, MacBook',
                'display_order' => 1,
            ],
            [
                'name' => 'Samsung',
                'description' => 'Thương hiệu điện tử Hàn Quốc với dòng Galaxy nổi tiếng',
                'display_order' => 2,
            ],
            [
                'name' => 'Xiaomi',
                'description' => 'Thương hiệu Trung Quốc với các sản phẩm chất lượng giá rẻ',
                'display_order' => 3,
            ],
            [
                'name' => 'OPPO',
                'description' => 'Thương hiệu điện thoại selfie chuyên nghiệp',
                'display_order' => 4,
            ],
            [
                'name' => 'Vivo',
                'description' => 'Thương hiệu điện thoại với camera ấn tượng',
                'display_order' => 5,
            ],
            [
                'name' => 'Realme',
                'description' => 'Thương hiệu trẻ trung với hiệu năng mạnh mẽ',
                'display_order' => 6,
            ],
            [
                'name' => 'OnePlus',
                'description' => 'Never Settle - Flagship killer',
                'display_order' => 7,
            ],
            [
                'name' => 'Sony',
                'description' => 'Thương hiệu Nhật Bản với công nghệ âm thanh và camera đỉnh cao',
                'display_order' => 8,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'description' => $brand['description'],
                'is_active' => true,
                'display_order' => $brand['display_order'],
            ]);
        }

        $this->command->info('✅ Đã tạo ' . count($brands) . ' thương hiệu');
    }
}
