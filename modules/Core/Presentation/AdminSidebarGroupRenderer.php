<?php
namespace Modules\Core\Presentation;

use Maatwebsite\Sidebar\Presentation\Illuminate\IlluminateGroupRenderer;
use Maatwebsite\Sidebar\Group;
use Illuminate\Contracts\View\Factory;

class AdminSidebarGroupRenderer extends IlluminateGroupRenderer
{
    /**
     * @var string
     */
    protected $view = 'sidebar.group';

    protected $viewNamespace;

    /**
     * @param Factory $factory
     * @param null $viewNamespace
     */
    public function __construct(Factory $factory, $viewNamespace = null)
    {
        parent::__construct($factory);
        $this->viewNamespace = $viewNamespace;
    }

    public function render(Group $group)
    {
        if ($group->isAuthorized()) {
            $items = [];
            foreach ($group->getItems() as $item) {
                $items[] = (new AdminSidebarItemRenderer($this->factory, $this->viewNamespace))->render($item);
            }
            if ($this->viewNamespace) {
                $this->view = $this->viewNamespace.'::'.$this->view;
            }

            return $this->factory->make($this->view, [
                'group' => $group,
                'items' => $items
            ])->render();
        }
    }
}
