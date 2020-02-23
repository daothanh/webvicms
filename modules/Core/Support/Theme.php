<?php

namespace Modules\Core\Support;

class Theme
{
    public function url($path)
    {
        $theme = $this->currentTheme();
        return asset("themes/$theme/$path");
    }

    /**
     * @param null $theme
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function info($theme = null)
    {
        $theme = $theme ?? $this->currentTheme();
        return json_decode(\File::get(base_path("themes/".ucfirst($theme)."/theme.json")));
    }

    public function settings($theme)
    {
        $theme = ucfirst($theme);
        $path = base_path("themes/$theme/settings.php");
        $settings = [];
        if (\File::exists($path)) {
            $settings = require($path);
        }
        return $settings;
    }

    public function path($theme = null)
    {
        $theme = $theme ?? $this->currentTheme();
        $theme = ucfirst($theme);
        return base_path('themes/'.$theme);
    }

    public function isAdminTheme($theme = null)
    {
        $theme = $this->info($theme);
        return $theme->type === 'admin';
    }

    public function isFrontendTheme($theme = null)
    {
        $theme = $this->info($theme);
        return $theme->type === 'frontend';
    }

    public function js($path)
    {
        $url = $this->url($path);
        return '<script src="'.$url.'"></script>';
    }

    public function css($path)
    {
        $url = $this->url($path);

        return '<link href="'.$url.'" rel="stylesheet">';
    }

    /**
     * Get all themes
     *
     * @param string $type
     * @return array themes
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function themes($type = 'frontend')
    {
        $themePath = base_path("themes");
        $directories = \File::directories($themePath);
        $themes = [];
        foreach ($directories as $themeDir) {
            $info = json_decode(\File::get("$themeDir/theme.json"));
            if (!$type || $info->type == $type) {
                $themes[] = $info;
            }
        }
        return $themes;
    }

    public function currentTheme()
    {
        $locale = locale_prefix();
        if ($locale !== null) {
            $pattern = '/^'.$locale.'\/admin\/?/';
        } else {
            $pattern = '/^admin\/?/';
        }

        if (preg_match($pattern, \Request::path())) {
            $theme = \Settings::get('website', 'admin_theme', 'admin');
        } else {
            $theme = \Settings::get('website', 'frontend_theme', 'simple');
        }

        return $theme;
    }
}
