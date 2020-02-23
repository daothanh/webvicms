<?php
namespace Modules\Core\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Presentation\Illuminate\IlluminateItemRenderer;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Presentation\ActiveStateChecker;

class AdminSidebarItemRenderer extends IlluminateItemRenderer
{
    protected $view = 'sidebar.item';
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

    public function render(Item $item)
    {
        if ($item->isAuthorized()) {
            $items = [];
            foreach ($item->getItems() as $child) {
                $items[] = (new AdminSidebarItemRenderer($this->factory, $this->viewNamespace))->render($child);
            }

            $badges = [];
            foreach ($item->getBadges() as $badge) {
                $badges[] = (new AdminSidebarBadgeRenderer($this->factory, $this->viewNamespace))->render($badge);
            }

            $appends = [];
            foreach ($item->getAppends() as $append) {
                $appends[] = (new AdminSidebarAppendRenderer($this->factory, $this->viewNamespace))->render($append);
            }

            if ($this->viewNamespace) {
                $this->view = $this->viewNamespace.'::'.$this->view;
            }

            return $this->factory->make($this->view, [
                'item'    => $item,
                'items'   => $items,
                'badges'  => $badges,
                'appends' => $appends,
                'active'  => (new ActiveStateChecker())->isActive($item),
            ])->render();
        }
    }
}
