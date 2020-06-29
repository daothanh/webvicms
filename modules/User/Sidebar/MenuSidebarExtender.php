<?php

namespace Modules\User\Sidebar;

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
        $menu->group(__('System'), function (Group $group) {
            $group->weight(2);

            $group->item(__('user::user.Accounts'), function (Item $item) {
                $item->weight(1);
                $item->icon('icon ion-md-contacts');
                $item->route('admin.user.index');
                $item->isActiveWhen(\App::getLocale()."/admin/(users|roles)/*");
                $item->authorize(
                    $this->auth->hasAccess('list users') || $this->auth->hasAccess('list roles')
                );

                $item->item(__('user::user.Users'), function (Item $item) {
                    $item->weight(1);
                    $item->icon('icon ion-md-contact');
                    $item->route('admin.user.index');
                    $item->authorize(
                        $this->auth->hasAccess('list users')
                    );
                });

                $item->item(__('user::user.Roles'), function (Item $item) {
                    $item->weight(1);
                    $item->icon('icon ion-md-umbrella');
                    $item->route('admin.role.index');
                    $item->authorize(
                        $this->auth->hasAccess('list roles')
                    );
                });
            });
        });
        return $menu;
    }
}
