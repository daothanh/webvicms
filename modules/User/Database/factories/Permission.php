<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\User\Entities\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->slug,
        'guard_name' => 'web',
        locale() => ['title' => $faker->name]
    ];
});
