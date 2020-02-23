<?php

namespace Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Support\Facades\Blade;
use Modules\Media\Blade\MediaThumbnailDirective;
use Modules\Media\Blade\MediaMultipleDirective;
use Modules\Media\Blade\MediaSingleDirective;
use Modules\Media\Blade\PublicMediaMultipleDirective;
use Modules\Media\Blade\PublicMediaSingleDirective;
use Modules\Media\Console\MediaRefresh;
use Modules\Media\Repositories\DeletingMedia;
use Modules\Media\Repositories\StoringMedia;
use Modules\Media\Listeners\HandleMediaStorage;
use Modules\Media\Listeners\RemovePolymorphicLink;
use Modules\Media\Sidebar\MenuSidebarExtender;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;

class MediaServiceProvider extends ServiceProvider
{
    use CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $commands = [
        MediaRefresh::class
    ];

    /**
     * Boot the application events.
     *
     * @param DispatcherContract $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->bidBladeDirectives();
        $this->registerBladeTags();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $events->listen(StoringMedia::class, HandleMediaStorage::class);
        $events->listen(DeletingMedia::class, RemovePolymorphicLink::class);
        $events->listen(\Modules\Media\Events\FolderIsDeleting::class, \Modules\Media\Listeners\DeleteFolderOnDisk::class);
        $events->listen(\Modules\Media\Events\FolderIsDeleting::class, \Modules\Media\Listeners\DeleteAllChildrenOfFolder::class);
        $events->listen(\Modules\Media\Events\FolderWasUpdated::class, \Modules\Media\Listeners\RenameFolderOnDisk::class);
        $events->listen(\Modules\Media\Events\FolderWasCreated::class, \Modules\Media\Listeners\CreateFolderOnDisk::class);

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
        $this->app->register(ImageServiceProvider::class);

        $this->commands($this->commands);
        $this->app->bind(
            'Modules\Media\Repositories\MediaRepository',
            function () {
                return new \Modules\Media\Repositories\Eloquent\MediaRepository(new \Modules\Media\Entities\Media());
            }
        );

        $this->app->bind(
            'Modules\Media\Repositories\FolderRepository',
            function () {
                return new \Modules\Media\Repositories\Eloquent\FolderRepository(new \Modules\Media\Entities\Media());
            }
        );
    }

    private function bidBladeDirectives()
    {
        $this->app->bind('public.media.single.directive', function () {
            return new PublicMediaSingleDirective();
        });
        $this->app->bind('public.media.multiple.directive', function () {
            return new PublicMediaMultipleDirective();
        });
        $this->app->bind('media.single.directive', function () {
            return new MediaSingleDirective();
        });
        $this->app->bind('media.multiple.directive', function () {
            return new MediaMultipleDirective();
        });
        $this->app->bind('media.thumbnail.directive', function () {
            return new MediaThumbnailDirective();
        });
    }

    private function registerBladeTags()
    {
        if (app()->environment() === 'testing') {
            return;
        }
        Blade::directive('publicMediaSingle', function ($value) {
            return "<?php echo PublicMediaSingleDirective::show([$value]); ?>";
        });

        Blade::directive('publicMediaMultiple', function ($value) {
            return "<?php echo PublicMediaMultipleDirective::show([$value]); ?>";
        });

        Blade::directive('mediaSingle', function ($value) {
            return "<?php echo MediaSingleDirective::show([$value]); ?>";
        });
        Blade::directive('mediaMultiple', function ($value) {
            return "<?php echo MediaMultipleDirective::show([$value]); ?>";
        });
        Blade::directive('thumbnail', function ($value) {
            return "<?php echo MediaThumbnailDirective::show([$value]); ?>";
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
            __DIR__ . '/../Config/config.php' => config_path('media.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'media'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/media');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/media';
        }, \Config::get('view.paths')), [$sourcePath]), 'media');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/media');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'media');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'media');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
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
