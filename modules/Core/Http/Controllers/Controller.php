<?php

namespace Modules\Core\Http\Controllers;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, SEOToolsTrait;

    protected $cookies = [];

    public function view($name, $data = [], $mergeData = [])
    {
        $namespace = \Settings::get('website', 'frontend_theme', 'simple');
        $themeView = "$namespace::";

        if (strpos($name, '::')) {
            $themeView .= "modules.".str_replace("::", ".", $name);
        } else {
            $themeView .= $name;
        }
        if (\Request::get('debug')) {
            $this->cookies['debug'] = 1;
        }
        if (\View::exists($themeView)) {
            $viewer = view($themeView, $data, $mergeData);
        } else {
            $viewer = view($name, $data, $mergeData);
        }

        foreach($this->cookies as $cookie => $val) {
            \Cookie::queue($cookie, $val);
        }

        return $viewer;
    }
}
