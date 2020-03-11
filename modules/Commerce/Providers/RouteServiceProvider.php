<?php

namespace Modules\Commerce\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Core\Traits\RouteServiceProviderTrait;

class RouteServiceProvider extends ServiceProvider
{
    use RouteServiceProviderTrait;
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Commerce\Http\Controllers';

    protected $modulePath = __DIR__."/..";
}
