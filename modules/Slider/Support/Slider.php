<?php

namespace Modules\Slider\Support;

use Modules\Slider\Repositories\SliderRepository;

class Slider
{
    protected $slider;

    public function __construct()
    {
        $this->slider = app(SliderRepository::class);
    }

    public function find($id)
    {
        $slider = $this->slider->newQueryBuilder()->with([
            'items' => function ($q) {
                $q->where('status', '=', 1);
            }])
            ->where('status', '=', 1)
            ->find($id);
        return $slider;
    }

    public function render($id, $template = 'carousel')
    {
        $slider = $this->find($id);
        if (!$slider) {
            return '';
        }
        $namespace = \Settings::get('website', 'frontend_theme', 'simple');
        $file = \Theme::path($namespace).'/views/slider/'.$template.'.blade.php';
        if(\File::exists($file)) {
            return view($namespace.'::slider.'.$template, compact('slider'))->render();
        }
        return view('slider::'.$template, compact('slider'))->render();
    }
}
