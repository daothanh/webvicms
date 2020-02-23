<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class PublicMediaMultipleDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'public.media.multiple.directive';
    }
}
