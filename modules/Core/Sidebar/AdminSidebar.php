<?php

namespace Modules\Core\Sidebar;

use Illuminate\Contracts\Container\Container;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use \Modules\Core\Repositories\Eloquent\AdminSidebar as AbstractAdminSideBar;
use Modules\User\Repositories\Authentication;

class AdminSidebar extends AbstractAdminSideBar {
    public $auth;
    public function __construct(Menu $menu, Container $container, Authentication $auth)
    {
        parent::__construct($menu, $container);
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }
}
