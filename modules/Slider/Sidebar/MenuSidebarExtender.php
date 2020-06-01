<?php

namespace Modules\Slider\Sidebar;

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

            $group->item(trans('slider::slider.title.Sliders'), function (Item $item) {
                $item->weight(30);
                $item->icon('icon ion-md-albums');
                $item->route('admin.slider.index');
                $item->authorize(
                    $this->auth->hasAccess('slider.list sliders')
                );
            });
        });
        return $menu;
    }
}
