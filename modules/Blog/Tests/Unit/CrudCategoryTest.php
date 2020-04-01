<?php

namespace Modules\Blog\Tests\Unit;

use Modules\Blog\Repositories\CategoryRepository;
use Modules\User\Entities\Permission;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Repositories\RoleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrudCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = app(CategoryRepository::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $category = $this->categoryRepository->create([
            'status' => 1,
            'vi' => [
                'name' => 'Sports',
                'slug' => 'sports'
            ]
        ]);
        $this->assertEquals('Sports', $category->name);


        /*$user = factory(User::class)->make(['name' => 'Admin']);
        $this->assertEquals('Admin', $user->name);
        $role = factory(Role::class)->make(['name' => 'admin']);
        $permission = factory(Permission::class)->make(['name' => 'blog.post_category.create category']);
        $this->assertEquals('blog.post_category.create category', $permission->name);
        $role->syncPermissions($permission);
        $user->assignRole($role);*/
        /*
        $data = [
            'status' => 1,
            'vi' => [
                'name' => 'Sports',
                'slug' => 'sports'
            ]
        ];
        $this->post(route('admin.blog.category.store'), $data)
            ->assertRedirect(route('admin.blog.category.index'));

        $category = $this->categoryRepository->find(1);
        $this->assertEquals('Sports', $category->name);*/
    }

    public function testRead()
    {
        $this->categoryRepository->create([
            'status' => 1,
            'vi' => [
                'name' => 'Sports',
                'slug' => 'sports'
            ]
        ]);
        $category = $this->categoryRepository->find(1);
        $this->assertEquals('Sports', $category->name);
    }

    public function testUpdate()
    {
        $category = $this->categoryRepository->create([
            'status' => 1,
            'vi' => [
                'name' => 'Sports',
                'slug' => 'sports'
            ]
        ]);
        $this->categoryRepository->update($category, ['vi' => ['name' => 'Food']]);
        $category = $this->categoryRepository->find(1);
        $this->assertEquals('Food', $category->name);
        $this->assertEquals('sports', $category->slug);
    }

    public function testDelete()
    {
        $this->categoryRepository->create([
            'status' => 1,
            'vi' => [
                'name' => 'Sports',
                'slug' => 'sports'
            ]
        ]);
        $category = $this->categoryRepository->find(1);
        $this->assertEquals('Sports', $category->name);

        $this->categoryRepository->destroy($category);

        $this->assertDatabaseMissing('blog__posts', ['id' => 1, 'status' => 1]);
        $this->assertDatabaseMissing('blog__post_translations', ['post_id' => 1, 'name' => 'Sport', 'slug' => 'sports']);
    }
}
