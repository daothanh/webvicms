<?php

namespace Modules\Commerce\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;

class CommerceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        collect([
            ['name' => 'list products', 'en' => ['title' => 'List all of products'], 'vi' => ['title' => 'Xem danh sách sản phẩm']],
            ['name' => 'create product', 'en' => ['title' => 'Create a product'], 'vi' => ['title' => 'Tạo sản phẩm']],
            ['name' => 'edit product', 'en' => ['title' => 'Edit a product'], 'vi' => ['title' => 'Cập nhật sản phẩm']],
            ['name' => 'delete product', 'en' => ['title' => 'Delete a product'], 'vi' => ['title' => 'Xóa sản phẩm']],
        ])->map(function ($permission) {
            $permission['name'] = "commerce.product.".$permission['name'];
            $exists = Permission::where('name', '=',$permission['name'])->first();
            if (!$exists)
                return Permission::create($permission);
            return null;
        });

        collect([
            ['name' => 'list product categories', 'en' => ['title' => 'List all of product\'s categories'], 'vi' => ['title' => 'Xem danh sách danh mục sản phẩm']],
            ['name' => 'create product category', 'en' => ['title' => 'Create a product\'s category'], 'vi' => ['title' => 'Tạo danh mục sản phẩm']],
            ['name' => 'edit product category', 'en' => ['title' => 'Edit a product\'s category'], 'vi' => ['title' => 'Cập nhật danh mục sản phẩm']],
            ['name' => 'delete product category', 'en' => ['title' => 'Delete a product\'s category'], 'vi' => ['title' => 'Xóa danh mục sản phẩm']],
        ])->map(function ($permission) {
            $permission['name'] = "commerce.product_category.".$permission['name'];
            $exists = Permission::where('name', '=',$permission['name'])->first();
            if (!$exists)
                return Permission::create($permission);
            return null;
        });
    }
}
