<?php

namespace Modules\Tag\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Core\Traits\RouteServiceProviderTrait;

class RouteServiceProvider extends ServiceProvider
{
    use RouteServiceProviderTrait;
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Tag\Http\Controllers';

    protected $modulePath = __DIR__."/..";
}
