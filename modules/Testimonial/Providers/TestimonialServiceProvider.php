<?php

namespace Modules\Testimonial\Providers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Testimonial\Sidebar\MenuSidebarExtender;

class TestimonialServiceProvider extends ServiceProvider
{
    use CanGetSidebarClassForModule;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Dispatcher $event)
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->registerRepositories();
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
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('testimonial.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'testimonial'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/testimonial');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/testimonial';
        }, \Config::get('view.paths')), [$sourcePath]), 'testimonial');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/testimonial');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'testimonial');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'testimonial');
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
    protected function registerRepositories()
    {
        $this->app->bind(
            'Modules\Testimonial\Repositories\TestimonialRepository',
            function () {
                return new \Modules\Testimonial\Repositories\Eloquent\TestimonialRepository(new \Modules\Testimonial\Entities\Testimonial());
            }
        );
    }
}
