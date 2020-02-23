<?php
namespace Modules\Core\Support;

class BreadcrumbItem {
    public $title;
    public $link;
    public $target;
    public $icon;
    public $classes;

    public function __construct($attributes = [])
    {
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $val) {
                if (property_exists(self::class, $attribute)) {
                    $this->{$attribute} = $val;
                }
            }
        }
    }

    public function getItemClass() {
        return $this->classes;
    }
}