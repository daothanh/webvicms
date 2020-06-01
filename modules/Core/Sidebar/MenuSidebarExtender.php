<?php

namespace Modules\Core\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;

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
            $group->weight(0);
            $group->item(__('Dashboard'), function (Item $item) {
                $item->weight(1);
                $item->icon('icon ion-md-apps');
                $item->route('admin');
                $item->isActiveWhen(locale_prefix()."/admin");
                $item->authorize(
                    true
                );
            });
        });

        $menu->group(__('System'), function (Group $group) {
            $group->weight(1);
            $group->item(trans('core::settings.title.Settings'), function (Item $item) {
                $item->weight(30);
                $item->icon('icon ion-md-construct');
                $item->route('admin.settings.index');
                $item->authorize(
                    $this->auth->hasRole('admin')
                );

                $item->item(trans('core::settings.title.General settings'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('icon ion-md-desktop');
                    $item->route('admin.settings.index');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });

                /*$item->item(__('Languages'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('icon ion-md-globe');
                    $item->route('admin.languages');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });*/

                /*$item->item(trans('core::settings.title.Account settings'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('icon ion-md-people');
                    $item->route('admin.settings.account');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });*/

                $item->item(trans('core::settings.title.Company'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('icon ion-md-business');
                    $item->route('admin.settings.company');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });

                $item->item(trans('core::settings.title.Mail Server'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('icon ion-md-mail');
                    $item->route('admin.settings.mail-server');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });

                $item->item(trans('core::core.Clear cache'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('icon ion-md-flash');
                    $item->route('admin.settings.clear_cache');
                    $item->authorize(
                        $this->auth->hasRole('admin')
                    );
                });
            });
        });
        return $menu;
    }
}
