<?php

namespace Modules\Slider\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;

class SliderDatabaseSeeder extends Seeder
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
            ['name' => 'list sliders', 'en' => ['title' => 'List all of sliders'], 'vi' => ['title' => 'Xem danh sách slider']],
            ['name' => 'create slider', 'en' => ['title' => 'Create a slider'], 'vi' => ['title' => 'Tạo slider']],
            ['name' => 'edit slider', 'en' => ['title' => 'Edit a slider'], 'vi' => ['title' => 'Cập nhật slider']],
            ['name' => 'delete slider', 'en' => ['title' => 'Delete a slider'], 'vi' => ['title' => 'Xóa slider']]
        ])->map(function ($permission) {
            $permission['name'] = "slider.".$permission['name'];
            $exists = Permission::where('name', '=',$permission['name'])->first();
            if (!$exists)
                return Permission::create($permission);
            return null;
        });
    }
}
