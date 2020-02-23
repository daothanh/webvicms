<?php
namespace Modules\Core\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Presentation\Illuminate\IlluminateAppendRenderer;

class AdminSidebarAppendRenderer extends IlluminateAppendRenderer
{
    protected $view = "sidebar.append";

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
