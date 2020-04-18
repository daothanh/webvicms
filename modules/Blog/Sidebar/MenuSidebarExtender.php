<?php

namespace Modules\Blog\Sidebar;

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

            $group->item(trans('blog::post.title.Posts'), function (Item $item) {
                $item->weight(6);
                $item->authorize(
                    $this->auth->hasAccess('blog.post.list posts') || $this->auth->hasAccess('blog.post_category.list categories')
                );
                $item->item(trans('blog::post.title.Posts'), function (Item $item) {
                    $item->weight(1);
                    $item->icon('icon ion-md-albums');
                    $item->route('admin.blog.post.index');
                    $item->authorize(
                        $this->auth->hasAccess('blog.post.list posts')
                    );
                });
                $item->item(trans('blog::category.title.Categories'), function (Item $item) {
                    $item->weight(1);
                    $item->icon('icon ion-md-albums');
                    $item->route('admin.blog.category.index');
                    $item->authorize(
                        $this->auth->hasAccess('blog.post_category.list categories')
                    );
                });
            });
        });

        return $menu;
    }
}
