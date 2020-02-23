<?php
namespace Modules\Core\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Maatwebsite\Sidebar\Sidebar;

class AdminSidebarRenderer implements SidebarRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'sidebar.menu';

    protected $viewNamespace;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        $this->viewNamespace = settings('website.admin_theme', 'admin');

        if ($this->viewNamespace) {
            $this->view = $this->viewNamespace.'::'.$this->view;
        }
    }

    public function render(Sidebar $sidebar)
    {
        $menu = $sidebar->getMenu();

        if ($menu->isAuthorized()) {
            $groups = [];
            foreach ($menu->getGroups() as $group) {
                $groups[] = (new AdminSidebarGroupRenderer($this->factory, $this->viewNamespace))->render($group);
            }

            return $this->factory->make($this->view, [
                'groups' => $groups
            ]);
        }
    }
}
