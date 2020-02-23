<?php

namespace Modules\Page\Sidebar;

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
            $group->item(trans('page::page.title.Pages'), function (Item $item) {
                $item->weight(1);
                $item->icon('icon ion-md-albums');
                $item->route('admin.page.index');
                $item->authorize(
                    $this->auth->hasRole('admin')
                );
            });
        });

        return $menu;
    }
}
