<?php

namespace Modules\Media\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AdminSidebar;

class MenuSidebarExtender extends AdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(__('Administration'), function (Group $group) {
            $group->weight(1);
            $group->item(__('Media'), function (Item $item) {
                $item->weight(30);
                $item->icon('icon ion-md-camera');
                $item->route('admin.media.index');
                $item->authorize(
                    $this->auth->hasAccess('list media')
                );
            });
        });
        return $menu;
    }
}
