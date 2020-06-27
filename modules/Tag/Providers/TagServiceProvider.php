<?php

namespace Modules\Tag\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Tag\Repositories\Cache\CacheTagRepository;
use Modules\Tag\Repositories\TagRepository;
use Modules\Tag\Blade\TagWidget;
use Illuminate\Support\Facades\Blade;
use Modules\Tag\Sidebar\MenuSidebarExtender;

class TagServiceProvider extends ServiceProvider
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
        $this->registerBladeTags();
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

        $this->app->bind(TagRepository::class, function () {
            $repository = new \Modules\Tag\Repositories\Eloquent\EloquentTagRepository(new \Modules\Tag\Entities\Tag());
            if (!config('app.cache')) {
                return $repository;
            }
            return new CacheTagRepository($repository);
        });

        $this->app->singleton(\Modules\Tag\Repositories\TagManager::class, function () {
            return new \Modules\Tag\Repositories\Eloquent\EloquentTagManagerRepository();
        });
        $this->app->singleton('tag.widget.directive', function ($app) {
            return new TagWidget($app[TagRepository::class]);
        });
    }


    private function registerBladeTags()
    {
        if (app()->environment() === 'testing') {
            return;
        }
        Blade::directive('tags', function ($value) {
            return "<?php echo TagWidget::show([$value]); ?>";
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
            __DIR__.'/../Config/config.php' => config_path('tag.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'tag'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/tag');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/tag';
        }, \Config::get('view.paths')), [$sourcePath]), 'tag');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/tag');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'tag');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'tag');
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
