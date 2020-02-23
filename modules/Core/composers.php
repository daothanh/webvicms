<?php
view()->composer('*', \Modules\Core\Composers\CurrentUserViewComposer::class);
view()->composer('admin::layouts.master', \Modules\Core\Composers\Admin\SidebarViewCreator::class);
