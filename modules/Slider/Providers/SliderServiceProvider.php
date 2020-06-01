<?php

namespace Modules\Slider\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Slider\Sidebar\MenuSidebarExtender;
use Modules\Slider\Support\Slider;

class SliderServiceProvider extends ServiceProvider
{
    use CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
            'Modules\Slider\Repositories\SliderRepository',
            function () {
                return new \Modules\Slider\Repositories\Eloquent\SliderRepository(new \Modules\Slider\Entities\Slider());
            }
        );

        $this->app->bind(
            'Modules\Slider\Repositories\SliderItemRepository',
            function () {
                return new \Modules\Slider\Repositories\Eloquent\SliderItemRepository(new \Modules\Slider\Entities\SliderItem());
            }
        );

        $this->app->singleton('slider', function () {
            return new Slider();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('slider.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'slider'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/slider');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/slider';
        }, \Config::get('view.paths')), [$sourcePath]), 'slider');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/slider');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'slider');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'slider');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
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
