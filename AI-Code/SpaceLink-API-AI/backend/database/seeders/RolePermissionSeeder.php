<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Quản trị viên', 'description' => 'Có toàn quyền quản lý hệ thống', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'staff', 'display_name' => 'Nhân viên', 'description' => 'Quản lý đơn hàng và sản phẩm', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'customer', 'display_name' => 'Khách hàng', 'description' => 'Người dùng mua hàng', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('roles')->insert($roles);

        // 2. Permissions
        $permissions = [
            ['id' => 1, 'name' => 'dashboard.view', 'display_name' => 'Xem Dashboard', 'group_name' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'products.view', 'display_name' => 'Xem sản phẩm', 'group_name' => 'products', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'products.create', 'display_name' => 'Thêm sản phẩm', 'group_name' => 'products', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'products.edit', 'display_name' => 'Sửa sản phẩm', 'group_name' => 'products', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'products.delete', 'display_name' => 'Xóa sản phẩm', 'group_name' => 'products', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'orders.view', 'display_name' => 'Xem đơn hàng', 'group_name' => 'orders', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'orders.edit', 'display_name' => 'Sửa đơn hàng', 'group_name' => 'orders', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'users.view', 'display_name' => 'Xem người dùng', 'group_name' => 'users', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'users.edit', 'display_name' => 'Sửa người dùng', 'group_name' => 'users', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'categories.manage', 'display_name' => 'Quản lý danh mục', 'group_name' => 'categories', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'vouchers.manage', 'display_name' => 'Quản lý voucher', 'group_name' => 'vouchers', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'comments.manage', 'display_name' => 'Quản lý bình luận', 'group_name' => 'comments', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'news.manage', 'display_name' => 'Quản lý tin tức', 'group_name' => 'news', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'name' => 'settings.manage', 'display_name' => 'Quản lý cấu hình', 'group_name' => 'settings', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('permissions')->insert($permissions);

        // 3. Role Permissions (Admin has all)
        foreach ($permissions as $p) {
            DB::table('role_permissions')->insert([
                'role_id' => 1,
                'permission_id' => $p['id']
            ]);
        }

        // Staff permissions
        $staff_permissions = [1, 2, 3, 4, 6, 7, 12];
        foreach ($staff_permissions as $p_id) {
            DB::table('role_permissions')->insert([
                'role_id' => 2,
                'permission_id' => $p_id
            ]);
        }
    }
}
