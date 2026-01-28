<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * 
     * Thứ tự quan trọng - các bảng phụ thuộc phải seed sau
     */
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu seed database SpaceLink...');
        $this->command->newLine();

        // 1. User mặc định (admin test)
        User::factory()->create([
            'name' => 'Admin SpaceLink',
            'email' => 'admin@spacelink.com',
        ]);
        $this->command->info('✅ Tạo admin user');

        // 2. Brands (không phụ thuộc bảng nào)
        $this->call(BrandSeeder::class);

        // 3. Categories (tự tham chiếu parent_id)
        $this->call(CategorySeeder::class);

        // 4. Products (phụ thuộc brands và categories)
        $this->call(ProductSeeder::class);

        $this->command->newLine();
        $this->command->info('🎉 Hoàn tất seed database!');
    }
}
