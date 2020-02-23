<?php

namespace Modules\Core\Repositories\Eloquent;

use Illuminate\Contracts\Container\Container;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\ShouldCache;
use Maatwebsite\Sidebar\Sidebar;
use Maatwebsite\Sidebar\Traits\CacheableTrait;
use Modules\Core\Events\BuildingSidebar;

class AdminSidebar implements Sidebar, ShouldCache
{
    use CacheableTrait;
    /**
     * @var Menu
     */
    protected $menu;

    /**
     * @var Container
     */
    protected $container;

    protected $menus;

    /**
     * @param Menu                $menu
     * @param Container           $container
     */
    public function __construct(Menu $menu, Container $container)
    {
        $this->menu = $menu;
        $this->container = $container;
        $this->menus = config('menu.admin');
    }

    /**
     * Build your sidebar implementation here
     */
    public function build()
    {
        event($event = new BuildingSidebar($this->menu));

        if ($this->menus) {
            foreach ($this->menus as $menu) {
                $name = $menu['name'];

                $class = 'Modules\\Core\\Sidebar\\' . $name . 'SidebarExtender';
                $this->addToSidebar($class);
            }
        }
    }

    /**
     * Add the given class to the sidebar collection
     * @param string $class
     */
    private function addToSidebar($class)
    {
        if (class_exists($class) === false) {
            return;
        }
        $extender = $this->container->make($class);

        $this->menu->add($extender->extendWith($this->menu));
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        $this->build();
        return $this->menu;
    }
}
