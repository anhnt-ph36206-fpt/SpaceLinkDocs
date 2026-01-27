<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed dữ liệu mẫu cho bảng products
     */
    public function run(): void
    {
        // Lấy ID của categories và brands
        $iphoneCategory = Category::where('slug', 'iphone')->first();
        $samsungCategory = Category::where('slug', 'samsung-galaxy')->first();
        $ipadCategory = Category::where('slug', 'ipad')->first();
        $macbookCategory = Category::where('slug', 'macbook')->first();
        $taingheCategory = Category::where('slug', 'tai-nghe')->first();

        $appleBrand = Brand::where('slug', 'apple')->first();
        $samsungBrand = Brand::where('slug', 'samsung')->first();

        if (!$iphoneCategory || !$appleBrand) {
            $this->command->error('❌ Vui lòng chạy BrandSeeder và CategorySeeder trước!');
            return;
        }

        $products = [
            // === iPhone ===
            [
                'category_id' => $iphoneCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPhone 16 Pro Max 256GB',
                'sku' => 'IP16PM-256',
                'description' => 'iPhone 16 Pro Max với chip A18 Pro mạnh mẽ, camera 48MP, màn hình 6.9 inch Super Retina XDR',
                'content' => '<h2>Thông số kỹ thuật</h2><ul><li>Màn hình: 6.9 inch Super Retina XDR</li><li>Chip: A18 Pro</li><li>RAM: 8GB</li><li>Bộ nhớ: 256GB</li><li>Camera: 48MP + 12MP + 12MP</li></ul>',
                'price' => 34990000,
                'sale_price' => 33990000,
                'quantity' => 50,
                'is_featured' => true,
            ],
            [
                'category_id' => $iphoneCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPhone 16 Pro 128GB',
                'sku' => 'IP16P-128',
                'description' => 'iPhone 16 Pro với camera chuyên nghiệp và chip A18 Pro',
                'price' => 28990000,
                'sale_price' => null,
                'quantity' => 80,
                'is_featured' => true,
            ],
            [
                'category_id' => $iphoneCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPhone 16 128GB',
                'sku' => 'IP16-128',
                'description' => 'iPhone 16 tiêu chuẩn với thiết kế mới, chip A18',
                'price' => 22990000,
                'sale_price' => 21990000,
                'quantity' => 100,
                'is_featured' => false,
            ],
            [
                'category_id' => $iphoneCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPhone 15 Pro Max 256GB',
                'sku' => 'IP15PM-256',
                'description' => 'iPhone 15 Pro Max với Titanium Design, chip A17 Pro',
                'price' => 29990000,
                'sale_price' => 27990000,
                'quantity' => 60,
                'is_featured' => true,
            ],
            [
                'category_id' => $iphoneCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPhone 15 128GB',
                'sku' => 'IP15-128',
                'description' => 'iPhone 15 với Dynamic Island, USB-C',
                'price' => 19990000,
                'sale_price' => 18490000,
                'quantity' => 120,
                'is_featured' => false,
            ],

            // === Samsung ===
            [
                'category_id' => $samsungCategory->id,
                'brand_id' => $samsungBrand->id,
                'name' => 'Samsung Galaxy S24 Ultra 256GB',
                'sku' => 'SS-S24U-256',
                'description' => 'Galaxy S24 Ultra với S Pen tích hợp, camera 200MP, AI thông minh',
                'price' => 31990000,
                'sale_price' => 29990000,
                'quantity' => 40,
                'is_featured' => true,
            ],
            [
                'category_id' => $samsungCategory->id,
                'brand_id' => $samsungBrand->id,
                'name' => 'Samsung Galaxy S24+ 256GB',
                'sku' => 'SS-S24P-256',
                'description' => 'Galaxy S24+ màn hình lớn, hiệu năng mạnh mẽ',
                'price' => 24990000,
                'sale_price' => null,
                'quantity' => 55,
                'is_featured' => false,
            ],
            [
                'category_id' => $samsungCategory->id,
                'brand_id' => $samsungBrand->id,
                'name' => 'Samsung Galaxy Z Fold6 256GB',
                'sku' => 'SS-ZF6-256',
                'description' => 'Điện thoại gập flagship với màn hình 7.6 inch khi mở',
                'price' => 41990000,
                'sale_price' => 39990000,
                'quantity' => 25,
                'is_featured' => true,
            ],

            // === iPad ===
            [
                'category_id' => $ipadCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPad Pro M4 11 inch 256GB',
                'sku' => 'IPAD-PRO-M4-11',
                'description' => 'iPad Pro với chip M4, màn hình OLED siêu mỏng',
                'price' => 28990000,
                'sale_price' => null,
                'quantity' => 35,
                'is_featured' => true,
            ],
            [
                'category_id' => $ipadCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPad Air M2 11 inch 128GB',
                'sku' => 'IPAD-AIR-M2-11',
                'description' => 'iPad Air mỏng nhẹ với chip M2 mạnh mẽ',
                'price' => 16990000,
                'sale_price' => 15990000,
                'quantity' => 45,
                'is_featured' => false,
            ],

            // === MacBook ===
            [
                'category_id' => $macbookCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'MacBook Pro 14 inch M3 Pro 512GB',
                'sku' => 'MBP-14-M3P-512',
                'description' => 'MacBook Pro với chip M3 Pro, màn hình Liquid Retina XDR',
                'price' => 49990000,
                'sale_price' => null,
                'quantity' => 20,
                'is_featured' => true,
            ],
            [
                'category_id' => $macbookCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'MacBook Air 15 inch M3 256GB',
                'sku' => 'MBA-15-M3-256',
                'description' => 'MacBook Air 15 inch siêu mỏng với chip M3',
                'price' => 32990000,
                'sale_price' => 31490000,
                'quantity' => 30,
                'is_featured' => false,
            ],

            // === Tai nghe ===
            [
                'category_id' => $taingheCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'AirPods Pro 2 USB-C',
                'sku' => 'APP2-USBC',
                'description' => 'AirPods Pro thế hệ 2 với cổng USB-C, chống ồn chủ động',
                'price' => 6790000,
                'sale_price' => 5990000,
                'quantity' => 150,
                'is_featured' => true,
            ],
            [
                'category_id' => $taingheCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'AirPods 4',
                'sku' => 'AP4',
                'description' => 'AirPods 4 thiết kế mới, âm thanh không gian',
                'price' => 3990000,
                'sale_price' => null,
                'quantity' => 200,
                'is_featured' => false,
            ],
            [
                'category_id' => $taingheCategory->id,
                'brand_id' => $samsungBrand->id,
                'name' => 'Samsung Galaxy Buds3 Pro',
                'sku' => 'SS-BUDS3P',
                'description' => 'Tai nghe không dây cao cấp với ANC và âm thanh 360',
                'price' => 5490000,
                'sale_price' => 4990000,
                'quantity' => 80,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            Product::create([
                'category_id' => $productData['category_id'],
                'brand_id' => $productData['brand_id'],
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'sku' => $productData['sku'],
                'description' => $productData['description'],
                'content' => $productData['content'] ?? null,
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'quantity' => $productData['quantity'],
                'sold_count' => rand(0, 100),
                'view_count' => rand(100, 5000),
                'is_featured' => $productData['is_featured'],
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Đã tạo ' . count($products) . ' sản phẩm mẫu');
    }
}
