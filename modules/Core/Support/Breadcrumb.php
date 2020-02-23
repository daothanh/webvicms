<?php

namespace Modules\Core\Support;

use Illuminate\Contracts\View\Factory;

class Breadcrumb
{
    /** @var Factory */
    protected $view;

    /** @var BreadcrumbItem[] */
    protected $items = [];

    public function __construct()
    {
        $this->view = app(Factory::class);
    }

    /**
     * Thêm mới một BreadcrumbItem
     *
     * @param $title
     * @param string $link
     * @param array $attributes
     */
    public function addItem($title, $link = '', $attributes = [])
    {
        $attributes = array_merge(compact('title', 'link'), $attributes);

        $this->items[] = new BreadcrumbItem($attributes);
    }

    public function render($viewFile = 'core::breadcrumb')
    {
        return $this->view->make($viewFile, ['items' => collect($this->items)]);
    }
}