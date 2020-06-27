<?php

namespace Modules\Commerce\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Commerce\Repositories\Cache\CacheCategoryRepository;
use Modules\Commerce\Repositories\Cache\CacheProductRepository;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Commerce\Sidebar\MenuSidebarExtender;

class CommerceServiceProvider extends ServiceProvider
{
    use CanGetSidebarClassForModule;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('menu', MenuSidebarExtender::class)
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(
            'Modules\Commerce\Repositories\ProductRepository',
            function () {
                $repository = new \Modules\Commerce\Repositories\Eloquent\EloquentProductRepository(new \Modules\Commerce\Entities\Product());
                if (! config('app.cache')) {
                    return $repository;
                }
                return new CacheProductRepository($repository);
            }
        );

        $this->app->bind(
            'Modules\Commerce\Repositories\CategoryRepository',
            function () {
                $repository = new \Modules\Commerce\Repositories\Eloquent\EloquentCategoryRepository(new \Modules\Commerce\Entities\Category());
                if (! config('app.cache')) {
                    return $repository;
                }
                return new CacheCategoryRepository($repository);
            }
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('commerce.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'commerce'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/commerce');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/commerce';
        }, \Config::get('view.paths')), [$sourcePath]), 'commerce');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/commerce');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'commerce');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'commerce');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
