<?php

if (!function_exists('languages')) {
    /**
     * Lấy về danh sách ngôn ngữ đang active
     *
     * @return Array
     */
    function languages()
    {
        if (env('APP_INSTALLED') !== true) {
            return [
                [
                    'code' => 'vi',
                    'native' => 'Tiếng Việt',
                    'name' => 'Vietnamese'
                ],
                [
                    'code' => 'en',
                    'native' => 'English',
                    'name' => 'English'
                ]
            ];
        }
        $cacheKey = 'active_languages';
        if (\Cache::has($cacheKey)) {
            return \Cache::get($cacheKey);
        }
        $languages = DB::table('languages')
            ->where('status', '=', 'Active')
            ->orderBy('default', 'desc')
            ->get(['id', 'code', 'name', 'native'])
            ->toArray();
        \Cache::put($cacheKey, $languages, now()->addMinutes(60));
        return $languages;
    }
}
if (!function_exists('default_language')) {
    function default_language()
    {
        if (env('APP_INSTALLED') !== true) {
            return [
                'code' => 'vi',
                'native' => 'Tiếng Việt',
                'name' => 'Vietnamese'
            ];
        }
        $cacheKey = 'default_language';
        if (\Cache::has($cacheKey)) {
            return \Cache::get($cacheKey);
        }
        $language = DB::table('languages')
            ->where('status', '=', 'Active')
            ->where('default', '=', 1)
            ->first();
        if ($language) {
            $language = [
                'id' => $language->id,
                'code' => $language->code,
                'name' => $language->name,
                'native' => $language->native
            ];
        } else {
            $language = null;
        }
        \Cache::put($cacheKey, $language, now()->addMinutes(60));
        return $language;
    }
}
if(!function_exists('get_language_by_code')) {
    function get_language_by_code($code) {
        return DB::table('languages')
            ->where('code', '=', $code)
            ->first();
    }
}
if (!function_exists('locales')) {
    /**
     * All active locales
     *
     * @return mixed
     */
    function locales()
    {
        return collect(languages())->pluck('code')->toArray();
    }
}
if (!function_exists('languages_by_code')) {
    /**
     * All active locales
     *
     * @return mixed
     */
    function languages_by_code()
    {
        return collect(languages())->pluck('native', 'code')->toArray();
    }
}

if (!function_exists('locale')) {
    /**
     * Current locale
     *
     * @return mixed
     */
    function locale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('locale_prefix')) {
    function locale_prefix()
    {
        $locale = \Request::segment(1);
        return ($locale && config('core.multiple_languages') && in_array($locale, locales())) ? $locale : null;
    }
}

if (!function_exists('site_name')) {
    /**
     * Get site's name
     *
     * @return mixed
     */
    function site_name()
    {
        return settings('website.name.' . locale(), config('app.name', 'Webvi Việt Nam'));
    }
}

if (!function_exists('site_description')) {
    /**
     * Get site's name
     *
     * @return mixed
     */
    function site_description()
    {
        return settings('website.description.' . locale(), config('app.name', 'Webvi Việt Nam'));
    }
}

if (!function_exists('site_keywords')) {
    /**
     * Get site's name
     *
     * @return mixed
     */
    function site_keywords()
    {
        return settings('website.keywords.' . locale(), config('app.name', 'Webvi Việt Nam'));
    }
}

if (!function_exists('settings')) {
    /**
     * Get a setting
     *
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    function settings($key, $default = null)
    {
        if (env('APP_INSTALLED')) {
            $keys = explode('.', $key);

            $category = $key;
            $key = '';
            $otherKey = '';

            if (count($keys) >= 2) {
                $category = array_shift($keys);
                $key = array_shift($keys);
                if (!empty($keys)) {
                    $otherKey = implode('.', $keys);
                }
            }

            $values = \Settings::get($category, $key);
            if ($values) {
                if ($otherKey !== '' && is_array($values)) {
                    return Arr::get($values, $otherKey);
                }
                return $values;
            }
        }
        return $default;
    }
}

if (!function_exists('get_logo')) {
    function get_logo($default = '')
    {
        $logo = (new \Modules\Core\Entities\Website())->logo;
        if ($logo) {
            return $logo->path->getUrl();
        }
        return $default;
    }
}

if (!function_exists('get_favicon')) {
    function get_favicon($default = '')
    {
        $favicon = (new \Modules\Core\Entities\Website())->favicon;
        if ($favicon) {
            return $favicon->path->getUrl();
        }
        return $default;
    }
}

if (!function_exists('clean_html')) {
    /**
     * Clean the html tags
     *
     * @param $content
     * @return string
     */
    function clean_html($content)
    {
        return trim(html_entity_decode(strip_tags(preg_replace('#(?:<br\s*/?>\s*?){2,}#', ' ', nl2br($content)))));
    }
}

if (!function_exists('append_params_to_current_url')) {
    /**
     * Append the params to current url
     *
     * @param $params
     * @return string
     */
    function append_params_to_current_url($params)
    {
        //Retrieve current query strings:
        $currentQueries = \Request::query();

        //Merge together current and new query strings:
        $allQueries = array_merge($currentQueries, $params);

        //Generate the URL with all the queries:
        return \Request::fullUrlWithQuery($allQueries);
    }
}

if (!function_exists('modules')) {
    /**
     * @param bool $activated
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function modules($activated = true)
    {
        $directories = \File::directories(base_path('modules'));
        $modules = [];
        $sortArr = [];
        foreach ($directories as $dir) {
            $path = $dir . "/module.json";
            if (\File::exists($path)) {
                $m = \File::get($path);
                $module = json_decode($m, true);
                if (!$activated || $module['active'] == true) {
                    $modules[$module['name']] = $module;
                    $sortArr[$module['name']] = $module['order'];
                }
            }
        }
        array_multisort($sortArr, SORT_ASC, $modules);
        return $modules;
    }
}


if (!function_exists('module_settings')) {
    /**
     * Get the settings of a module
     *
     * @param $module Module name
     * @return array|mixed
     */
    function module_settings($module)
    {
        $path = base_path("modules/" . ucwords($module) . "/Config/settings.php");
        $settings = [];
        if (\File::exists($path)) {
            $settings = require($path);
        }
        return $settings;
    }
}

if (!function_exists('get_module_settings')) {

    /**
     * Get the settings of the all of modules
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function get_module_settings()
    {
        $modules = modules();
        $mSettings = [];
        foreach ($modules as $module) {
            $s = module_settings($module['name']);
            if ($s) {
                $mSettings[$module['name']] = $s;
            }
        }
        return $mSettings;
    }
}

if (!function_exists('theme_name')) {
    function theme_name($default = 'simple')
    {
        return \Settings::get('website', 'frontend_theme', $default);
    }
}

if (!function_exists('form_locale_has_errors')) {
    function form_locale_has_errors($errors, $locale)
    {
        if ($errors instanceof Illuminate\Support\ViewErrorBag) {
            $errors = $errors->getMessages();
        }
        foreach ($errors as $key => $val) {
            if (preg_match('/^' . $locale . '\./', $key)) {
                return true;
            }
        }
        return false;
    }
}
