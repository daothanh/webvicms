<?php

namespace Modules\Blog\Tests\Unit;

use Modules\Blog\Repositories\CategoryRepository;
use Modules\User\Entities\Permission;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Entities\UserToken;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrudCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryRepository;

    protected $data;

    protected $locale;

    public function setUp(): void
    {
        parent::setUp();
        $this->locale = locale();
        $this->data = [
            'status' => 1,
            $this->locale => [
                'name' => 'Sports',
                'slug' => 'sports'
            ]
        ];

        $this->categoryRepository = app(CategoryRepository::class);
    }

    /**
     * A http unit test that execute the post method.
     *
     * @return void
     */
    public function testCreate()
    {
        $user = $this->createUser('blog.post_category.create category');

        // Create a category
        $this->actingAs($user)
            ->post(route('admin.blog.category.store'), $this->data)
            ->assertRedirect(route('admin.blog.category.index'));

        // Update a category
        $this->actingAs($user)
            ->post(route('admin.blog.category.store'), ['id' => 1, 'status' => 1, $this->locale => ['name' => 'Food']])
            ->assertRedirect(route('admin.blog.category.index'));
        $this->assertDatabaseHas('blog__category_translations', ['category_id' => 1, 'name' => 'Food', 'slug' => $this->data[$this->locale]['slug'], 'locale' => $this->locale]);

        // Delete a category by API
        $user = $this->createUser('blog.post_category.delete category');
        $this->actingAs($user)
            ->withHeader('Authorization', "Bearer ".$user->getFirstToken()->access_token)
            ->delete(route('api.blog.category.delete', ['id' => 1]))
            ->assertExactJson(['error' => false]);

        $this->assertDatabaseMissing('blog__categories', ['id' => 1, 'status' => 1, 'deleted_at' => null]);
    }

    protected function createUser($permissionName, $guardName = 'web')
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        factory(UserToken::class)->create(['user_id' => $user->id]);
        $role = factory(Role::class)->create(['name' => 'admin', 'guard_name' => $guardName]);
        $permission = factory(Permission::class)->create(['name' => $permissionName, 'guard_name' => $guardName]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);
        return $user;
    }
}
