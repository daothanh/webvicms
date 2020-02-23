<?php

namespace Modules\Core\Composers\Admin;

use Illuminate\Contracts\View\View;
use Modules\Core\Presentation\AdminSidebarRenderer;
use Modules\Core\Sidebar\AdminSidebar;

class SidebarViewCreator
{
    /**
     * @var AdminSidebar
     */
    protected $sidebar;

    /**
     * @var AdminSidebarRenderer
     */
    protected $renderer;

    /**
     * @param AdminSidebar    $sidebar
     * @param AdminSidebarRenderer $renderer
     */
    public function __construct(AdminSidebar $sidebar, AdminSidebarRenderer $renderer)
    {
        $this->sidebar = $sidebar;
        $this->renderer = $renderer;
    }

    public function create($view)
    {
        $view->sidebar = $this->renderer->render($this->sidebar);
    }

    public function compose(View $view)
    {
        $this->create($view);
    }
}
