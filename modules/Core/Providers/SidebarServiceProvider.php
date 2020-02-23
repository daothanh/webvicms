<?php

namespace Modules\Core\Providers;

use Modules\Core\Composers\Admin\SidebarViewCreator;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\SidebarManager;
use Modules\Core\Sidebar\AdminSidebar;

class SidebarServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
    }

    public function boot(SidebarManager $manager)
    {
        $manager->register(AdminSidebar::class);
        $adminTheme = \Settings::get('website', 'admin_theme', 'admin');
        \View::share('adminTheme',$adminTheme);
        \View::creator($adminTheme.'::partials.sidebar-nav', SidebarViewCreator::class);
    }
}
