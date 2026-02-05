<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'role_id' => 1, // Admin
            'email' => 'admin@spacelink.com',
            'password' => Hash::make('password'),
            'fullname' => 'System Administrator',
            'phone' => '0123456789',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'role_id' => 3, // Customer
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'fullname' => 'Test Customer',
            'phone' => '0987654321',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
