<?php
if (!function_exists('page')) {
    /**
     * Get a page
     *
     * @param $id
     * @return mixed
     */
    function page($id)
    {
        return app(\Modules\Page\Repositories\PageRepository::class)->find($id);
    }
}
