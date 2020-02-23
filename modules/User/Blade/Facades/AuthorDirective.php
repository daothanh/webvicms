<?php

namespace Modules\User\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class AuthorDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'user.author.directive';
    }
}
