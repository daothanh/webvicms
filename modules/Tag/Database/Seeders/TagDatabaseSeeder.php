<?php

namespace Modules\Tag\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;

class TagDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        collect([['name' => 'list tags', 'en' => ['title' => 'List all of tags'], 'vi' => ['title' => 'Xem danh sách thẻ']],
            ['name' => 'create tag', 'en' => ['title' => 'Create a tag'], 'vi' => ['title' => 'Tạo thẻ']],
            ['name' => 'edit tag', 'en' => ['title' => 'Edit a tag'], 'vi' => ['title' => 'Cập nhật thẻ']],
            ['name' => 'delete tag', 'en' => ['title' => 'Delete a tag'], 'vi' => ['title' => 'Xóa thẻ']],
        ])->map(function ($permission) {
            return Permission::create($permission);
        });
    }
}
