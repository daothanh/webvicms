<?php

namespace Modules\Testimonial\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;

class TestimonialDatabaseSeeder extends Seeder
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
            ['name' => 'list testimonials', 'en' => ['title' => 'List all of testimonials'], 'vi' => ['title' => 'Xem danh sách lời nhận xét']],
            ['name' => 'create testimonial', 'en' => ['title' => 'Create a testimonial'], 'vi' => ['title' => 'Tạo lời nhận xét']],
            ['name' => 'edit testimonial', 'en' => ['title' => 'Edit a testimonial'], 'vi' => ['title' => 'Cập nhật lời nhận xét']],
            ['name' => 'delete testimonial', 'en' => ['title' => 'Delete a testimonial'], 'vi' => ['title' => 'Xóa lời nhận xét']]
        ])->map(function ($permission) {
            $permission['name'] = "testimonial.".$permission['name'];
            $exists = Permission::where('name', '=',$permission['name'])->first();
            if (!$exists)
                return Permission::create($permission);
            return null;
        });
    }
}
