<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;

class BlogDatabaseSeeder extends Seeder
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
            ['name' => 'list posts', 'en' => ['title' => 'List all of posts'], 'vi' => ['title' => 'Xem danh sách bài viết']],
            ['name' => 'create post', 'en' => ['title' => 'Create a post'], 'vi' => ['title' => 'Tạo bài viết']],
            ['name' => 'edit post', 'en' => ['title' => 'Edit a post'], 'vi' => ['title' => 'Cập nhật bài viết']],
            ['name' => 'delete post', 'en' => ['title' => 'Delete a post'], 'vi' => ['title' => 'Xóa bài viết']],
        ])->map(function ($permission) {
            $permission['name'] = "blog.post.".$permission['name'];
            $exists = Permission::where('name', '=', $permission['name'])->first();
            if (!$exists)
                return Permission::create($permission);
            return null;
        });

        collect([
            ['name' => 'list categories', 'en' => ['title' => 'List all of categories'], 'vi' => ['title' => 'Xem danh sách danh mục']],
            ['name' => 'create category', 'en' => ['title' => 'Create a category'], 'vi' => ['title' => 'Tạo danh mục']],
            ['name' => 'edit category', 'en' => ['title' => 'Edit a category'], 'vi' => ['title' => 'Cập nhật danh mục']],
            ['name' => 'delete category', 'en' => ['title' => 'Delete a category'], 'vi' => ['title' => 'Xóa danh mục']],
        ])->map(function ($permission) {
            $permission['name'] = "blog.post_category.".$permission['name'];
            $exists = Permission::where('name', '=',$permission['name'])->first();
            if (!$exists)
                return Permission::create($permission);
            return null;
        });
    }
}
