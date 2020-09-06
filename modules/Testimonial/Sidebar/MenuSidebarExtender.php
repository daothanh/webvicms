<?php

namespace Modules\Testimonial\Sidebar;

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
            $group->item(trans('testimonial::testimonial.title.Testimonials'), function (Item $item) {
                $item->weight(3);
                $item->icon('icon ion-md-chatbubbles');
                $item->route('admin.testimonial.index');
                $item->authorize(
                    $this->auth->hasAccess('testimonial.list testimonials')
                );

            });

        });
        return $menu;
    }
}
