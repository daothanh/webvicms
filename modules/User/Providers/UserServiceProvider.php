<?php

namespace Modules\User\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Events\Verified;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\User\Auth\AccessTokenGuard;
use Modules\User\Auth\Authentication;
use Modules\User\Console\CreateUser;
use Modules\User\Console\MakeUserToken;
use Modules\User\Console\UserChangePassword;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Entities\UserToken;
use Modules\User\Events\UserWasCreated;
use Modules\User\Http\Middleware\Admin;
use Modules\User\Http\Middleware\ApiPermission;
use Modules\User\Http\Middleware\ApiRole;
use Modules\User\Http\Middleware\ApiTokenAuth;
use Modules\User\Listeners\EmailWelcomeListener;
use Modules\User\Repositories\Eloquent\RoleRepository;
use Modules\User\Repositories\Eloquent\UserRepository;
use Modules\User\Repositories\Eloquent\UserTokenRepository;
use Modules\User\Sidebar\MenuSidebarExtender;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Modules\User\Blade\AuthorDirective;

class UserServiceProvider extends ServiceProvider
{
    use CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $commands = [
        MakeUserToken::class,
        CreateUser::class,
        UserChangePassword::class
    ];

    protected $middleware = [
        'admin' => Admin::class,
        'api.auth' => ApiTokenAuth::class,
        'api.role' => ApiRole::class,
        'api.permission' => ApiPermission::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
    ];
    /**
     * Boot the application events.
     *
     * @param Dispatcher $event
     * @return void
     */
    public function boot(Dispatcher $event)
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->registerFactories();
        $this->registerRepositories();
        $this->bidBladeDirectives();
        $this->registerBladeTags();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerMiddleware($this->app['router']);

        $event->listen(Verified::class, EmailWelcomeListener::class);
        $event->listen(UserWasCreated::class, \Modules\User\Listeners\MakeUserToken::class);

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('menu', MenuSidebarExtender::class)
        );
    }
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $name => $middleware) {
            $router->aliasMiddleware($name, $middleware);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->app->register(RouteServiceProvider::class);
//        $this->app->register(TokenToUserProvider::class);
        $this->commands($this->commands);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('user.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'user'
        );

        $this->app->make('config')->set('user.account.register', settings('account.register.off', 0) ? false : true);
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/user');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/user';
        }, Config::get('view.paths')), [$sourcePath]), 'user');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/user');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'user');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'user');
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

    protected function registerRepositories()
    {
        $this->app->bind(
            'Modules\User\Repositories\UserRepository',
            function () {
                return new UserRepository(new User());
            }
        );

        $this->app->bind(
            'Modules\User\Repositories\UserTokenRepository',
            function () {
                return new UserTokenRepository(new UserToken());
            }
        );
        $this->app->bind(
            'Modules\User\Repositories\Authentication',
            function () {
                return new Authentication();
            }
        );
        $this->app->bind(
            'Modules\User\Repositories\RoleRepository',
            function () {
                return new RoleRepository(new Role());
            }
        );
        $this->app->singleton('user_token', function () {
            return new TokenToUserProvider(
                $this->app->make('Modules\User\Repositories\UserRepository'),
                $this->app->make('Modules\User\Repositories\UserTokenRepository')
            );
        });
    }

    private function bidBladeDirectives()
    {
        $this->app->bind('user.author.directive', function () {
            return new AuthorDirective();
        });
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

    private function registerBladeTags()
    {
        if (app()->environment() === 'testing') {
            return;
        }
        Blade::directive('author', function ($value) {
            return "<?php echo AuthorDirective::show([$value]); ?>";
        });
    }
}
