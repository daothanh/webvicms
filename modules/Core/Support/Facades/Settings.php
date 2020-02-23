<?php

namespace Modules\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}
