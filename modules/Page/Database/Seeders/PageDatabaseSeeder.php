<?php

namespace Modules\Page\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;

class PageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        collect([
            ['name' => 'list pages', 'en' => ['title' => 'List all of pages'], 'vi' => ['title' => 'Xem danh sách trang']],
            ['name' => 'create page', 'en' => ['title' => 'Create a page'], 'vi' => ['title' => 'Tạo trang']],
            ['name' => 'edit page', 'en' => ['title' => 'Edit a page'], 'vi' => ['title' => 'Cập nhật trang']],
            ['name' => 'delete page', 'en' => ['title' => 'Delete a page'], 'vi' => ['title' => 'Xóa trang']],
        ])->map(function ($permission) {
            $permission['name'] = "page.".$permission['name'];
            $exists = Permission::where('name', '=',$permission['name'])->first();
            if (!$exists)
                return Permission::create($permission);
            return null;
        });
    }
}
