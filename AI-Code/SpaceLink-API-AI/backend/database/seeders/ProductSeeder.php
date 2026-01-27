<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'category_id' => 5, // iPhone
                'brand_id' => 1, // Apple
                'name' => 'iPhone 15 Pro Max 256GB',
                'slug' => 'iphone-15-pro-max-256gb',
                'sku' => 'IP15PM256',
                'description' => 'Siêu phẩm mới nhất từ Apple với khung viền Titan.',
                'price' => 34990000,
                'sale_price' => 32990000,
                'quantity' => 100,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 6, // Samsung Galaxy
                'brand_id' => 2, // Samsung
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'sku' => 'S24U',
                'description' => 'Quyền năng Galaxy AI trong tầm tay.',
                'price' => 33990000,
                'sale_price' => 29990000,
                'quantity' => 50,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('products')->insert($products);

        // Simple variants
        DB::table('product_variants')->insert([
            [
                'product_id' => 1,
                'sku' => 'IP15PM256-BLACK',
                'price' => 32990000,
                'quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
