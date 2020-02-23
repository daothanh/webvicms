<?php
namespace Modules\Core\Presentation;

use Maatwebsite\Sidebar\Presentation\Illuminate\IlluminateBadgeRenderer;
use Illuminate\Contracts\View\Factory;

class AdminSidebarBadgeRenderer extends IlluminateBadgeRenderer
{
    protected $view = "sidebar.badge";

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory, $viewNamespace = null)
    {
        $this->factory = $factory;

        if ($viewNamespace) {
            $this->view = $viewNamespace.'::'.$this->view;
        }
    }
}
