<?php

namespace Modules\Commerce\Sidebar;

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
            $group->weight(10);
            $group->item(trans('commerce::product.title.Products'), function (Item $item) {
                $item->item(trans('commerce::product.title.Products'), function (Item $item) {
                    $item->weight(1);
                    $item->icon('icon ion-md-albums');
                    $item->route('admin.commerce.product.index');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });
                $item->item(trans('commerce::category.title.Categories'), function (Item $item) {
                    $item->weight(1);
                    $item->icon('icon ion-md-albums');
                    $item->route('admin.commerce.category.index');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });
            });
        });

        return $menu;
    }
}
