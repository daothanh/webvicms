<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class PublicMediaSingleDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'public.media.single.directive';
    }
}
