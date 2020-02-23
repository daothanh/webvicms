<?php

namespace Modules\Core\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\InstallApp;
use Modules\Core\Contracts\DeletingSeo;
use Modules\Core\Contracts\StoringSeo;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Http\Middleware\SaveSettings;
use Modules\Core\Listeners\HandleEntityChange;
use Modules\Core\Listeners\HandleEntityDeleting;
use Modules\Core\Sidebar\MenuSidebarExtender;
use Modules\Core\Support\Settings;
use Modules\Core\Support\Theme;
use Modules\Core\Traits\CanGetSidebarClassForModule;

class CoreServiceProvider extends ServiceProvider
{
    use CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $commands = [
        \Modules\Core\Console\Theme::class
    ];

    protected $middleware = [
        'save.settings' => SaveSettings::class,

    ];

    protected $groupMiddleware = [
        'web' => [
            'save.settings',
        ],
        /*'api.token' => [
            'throttle:60,1',
            'bindings',
            'save.settings',
        ]*/
    ];

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
        $this->registerThemes();
        $this->registerMiddleware($this->app['router']);
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('menu', MenuSidebarExtender::class)
        );
        $this->app['events']->listen(StoringSeo::class, HandleEntityChange::class);
        $this->app['events']->listen(DeletingSeo::class, HandleEntityDeleting::class);
        $this->app->register(CoreMailServiceProvider::class);


        $config = $this->app->make('config');

        $fbConfigs = $config->get('services.facebook', []);
        $config->set('services.facebook', array_merge($fbConfigs, settings('account.facebook', [])));

        $ggConfigs = $config->get('services.google', []);
        $config->set('services.google', array_merge($ggConfigs, settings('account.facebook', [])));
    }

    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $name => $middleware) {
            $router->aliasMiddleware($name, $middleware);
        }

        foreach ($this->groupMiddleware as $group => $middlewares) {
            foreach ($middlewares as $mw) {
                $router->pushMiddlewareToGroup($group, $mw);
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('settings', function () {
            return new Settings();
        });

        $this->app->singleton('theme', function () {
            return new Theme();
        });
        $this->registerConfig();
        $this->setLocale();
        $this->app->register('\Maatwebsite\Sidebar\SidebarServiceProvider');
        $this->app->register(SidebarServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->commands($this->commands);

        $this->app->singleton(ExceptionHandler::class, \Modules\Core\Exceptions\Handler::class);
    }

    public function setLocale()
    {
        $locale = $this->app['request']->segment(1);
        $locale = ($locale && config('core.multiple_languages') && in_array($locale, $this->locales())) ? $locale : null;

        $defaultLang = $this->defaultLanguage();
        if ($locale !== null) {
            if ($locale === $defaultLang['code']) {
                return $this->clearLocale();
            }
        } else {
            $locale = $defaultLang['code'];
        }

        $this->app->setLocale($locale);
        $locales = $this->locales();

        $this->app['config']->set('app.default_locale', $defaultLang['code']);
        $this->app['config']->set('app.fallback_locale', $defaultLang['code']);
        $this->app['config']->set('translatable.fallback_locale', $defaultLang['code']);
        $this->app['config']->set('app.locale', $locale);
        $this->app['config']->set('translatable.locale', $locale);
        $this->app['config']->set('translatable.locales', $locales);
    }

    protected function defaultLanguage()
    {
        if ($this->app->runningInConsole() || env('APP_INSTALLED') !== true) {
            return ['id' => 19, 'code' => 'vi', 'native' => 'Tiếng việt'];
        }
        $cacheKey = 'default_language';
        if ($this->app['cache']->has($cacheKey)) {
            return $this->app['cache']->get($cacheKey);
        }
        $language = $this->app['db']->table('languages')
            ->where('status', '=', 'Active')
            ->where('default', '=', 1)
            ->first();
        if ($language) {
            $language = [
                'id' => $language->id,
                'code' => $language->code,
                'name' => $language->name,
                'native' => $language->native
            ];
        } else {
            $language = null;
        }
        $this->app['cache']->put($cacheKey, $language, now()->addMinutes(60));
        return $language;
    }

    protected function locales()
    {
        return collect($this->languages())->pluck('code')->toArray();
    }

    protected function languages()
    {
        if ($this->app->runningInConsole() || env('APP_INSTALLED') !== true) {
            return [
                ['id' => 19, 'code' => 'vi', 'native' => 'Tiếng việt'],
                ['id' => 109, 'code' => 'en', 'native' => 'English'],
            ];
        }
        $cacheKey = 'active_languages';
        if ($this->app['cache']->has($cacheKey)) {
            return $this->app['cache']->get($cacheKey);
        }
        $languages = $this->app['db']->table('languages')
            ->where('status', '=', 'Active')
            ->orderBy('default', 'desc')
            ->get(['id', 'code', 'name', 'native'])
            ->toArray();
        $this->app['cache']->put($cacheKey, $languages, now()->addMinutes(60));
        return $languages;
    }

    protected function clearLocale()
    {
        $segments = [];
        foreach ($this->app['request']->segments() as $i => $seg) {
            if ($i > 0) {
                $segments[$i-1] = $seg;
            }
        }
        return $this->app['redirect']->to(implode('/', $segments), 301)->send();
    }
    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('core.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'core'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/core');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/core';
        }, \Config::get('view.paths')), [$sourcePath]), 'core');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/core');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'core');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');
        }
    }

    /**
     * Regsiter the themes and languages
     */
    public function registerThemes()
    {
        $adminTheme = settings('website.admin_theme', 'admin');
        $frontendTheme = settings('website.frontend_theme', 'simple');
        $this->app['view']->addNamespace($adminTheme, base_path('themes/' . ucfirst($adminTheme) . '/views'));
        $this->app['view']->addNamespace($frontendTheme, base_path('themes/' . ucfirst($frontendTheme) . '/views'));

        // Needed to clear the views cache.
        $this->app['view']->getFinder()->flush();

        $this->app['view']->share('themeName', $frontendTheme);

        //Language
        $langPath = base_path("themes/".ucfirst($adminTheme)."/lang");
        $this->app['translator']->addNamespace($adminTheme, $langPath);

        $langPath = base_path("themes/".ucfirst($frontendTheme)."/lang");
        $this->app['translator']->addNamespace($frontendTheme, $langPath);
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
