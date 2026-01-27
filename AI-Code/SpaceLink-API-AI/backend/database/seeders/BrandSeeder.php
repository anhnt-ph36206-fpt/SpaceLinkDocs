<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple', 'is_active' => true, 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samsung', 'slug' => 'samsung', 'is_active' => true, 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'is_active' => true, 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'OPPO', 'slug' => 'oppo', 'is_active' => true, 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vivo', 'slug' => 'vivo', 'is_active' => true, 'display_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('brands')->insert($brands);
    }
}
