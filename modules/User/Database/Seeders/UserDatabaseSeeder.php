<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;
use Modules\User\Entities\Role;
use Modules\User\Repositories\UserRepository;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        // create permissions for an admin
        collect([
            ['name' => 'list users', 'en' => ['title' => 'List all of users'], 'vi' => ['title' => 'Xem danh sách tài khoản']],
            ['name' => 'create user', 'en' => ['title' => 'Create a user'], 'vi' => ['title' => 'Tạo tài khoản']],
            ['name' => 'edit user', 'en' => ['title' => 'Edit a user'], 'vi' => ['title' => 'Cập nhật tài khoản']],
            ['name' => 'delete user', 'en' => ['title' => 'Delete a user'], 'vi' => ['title' => 'Xóa tài khoản']],

            ['name' => 'list roles', 'en' => ['title' => 'List all of roles'], 'vi' => ['title' => 'Xem danh sách vai trò']],
            ['name' => 'create role', 'en' => ['title' => 'Create a role'], 'vi' => ['title' => 'Tạo vai trò']],
            ['name' => 'edit role', 'en' => ['title' => 'Edit a role'], 'vi' => ['title' => 'Cập nhật vai trò']],
            ['name' => 'delete role', 'en' => ['title' => 'Delete a role'], 'vi' => ['title' => 'Xóa vai trò']],
        ])->map(function ($permission) {
            return Permission::create($permission);
        });
        // add admin role
        $adminRole = Role::create(['name' => 'admin', 'en' => ['title' => 'Administrator'], 'vi' => ['title' => 'Quản trị viên']]);
        $adminPermissions = Permission::all();
        $adminRole->givePermissionTo($adminPermissions);
        // add a default user role
        Role::create(['name' => 'user', 'en' => ['title' => 'User'], 'vi' => ['title' => 'Thành viên']]);
    }
}
