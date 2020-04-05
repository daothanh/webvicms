<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Route;

trait RouteServiceProviderTrait
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $locale = locale_prefix();
        if ($locale !== null) {
            Route::group(['prefix' => $locale], function () {
                $this->routes();
            });
        } else {
            $this->routes();
        }
    }

    protected function routes()
    {
        $this->mapApiRoutes();
        $this->mapWebAdminRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web'])
            ->namespace($this->moduleNamespace)
            ->group(realpath($this->modulePath . '/Routes/web.php'));
    }

    protected function mapWebAdminRoutes()
    {
        $adminRoutesFile = realpath($this->modulePath . '/Routes/admin.php');
        if (\File::exists($adminRoutesFile)) {
            Route::prefix('admin')
                ->middleware(['web', 'admin'])
                ->namespace($this->moduleNamespace . '\Admin')
                ->group($adminRoutesFile);
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware(['api'])
            ->namespace($this->moduleNamespace . '\Api')
            ->group(realpath($this->modulePath . '/Routes/api.php'));
    }
}
