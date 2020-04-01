<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\User\Entities\Role;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->slug,
        'guard_name' => 'web',
        'vi' => ['title' => $faker->name]
    ];
});
