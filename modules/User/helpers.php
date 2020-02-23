<?php

use Modules\User\Repositories\UserRepository;

if (!function_exists('can_register')) {
    function can_register() {
        return config('user.account.register');
    }
}

if (!function_exists('get_user_by_id')) {
    function get_user_by_id($id) {
        return app(UserRepository::class)->find($id);
    }
}
