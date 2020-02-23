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

    public function view($name, $data = [], $mergeData = [])
    {
        $namespace = \Settings::get('website', 'frontend_theme', 'simple');
        $themeView = "$namespace::";

        if (strpos($name, '::')) {
            $themeView .= "modules.".str_replace("::", ".", $name);
        } else {
            $themeView .= $name;
        }

        \View::share('themeName', $namespace);

        if (\View::exists($themeView)) {
            return view($themeView, $data, $mergeData);
        }

        return view($name, $data, $mergeData);
    }
}
