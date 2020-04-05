<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\User\Entities\UserToken;
use Ramsey\Uuid\Uuid;

$factory->define(UserToken::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'access_token' => Uuid::uuid4()->toString()
    ];
});
