<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\BaseAdminController;
use Illuminate\Http\Request;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        return $this->view('core::admin.dashboard');
    }

    public function changeLang($locale, Request $request)
    {
        $url = $request->server('HTTP_REFERER');
        $parts = parse_url($url);
        $pathArr = explode('/', $parts['path']);
        $locales = locales();

        if (in_array($pathArr[1], $locales)) {
            unset($pathArr[1]);
        } else {
            $tmpArr = [];
            $tmpArr[0] = $pathArr[0];
            $tmpArr[1] = $locale;
            foreach ($pathArr as $index => $seg) {
                if ($index >= 1) {
                    $tmpArr[$index+1] = $pathArr[$index];
                } else {
                    $tmpArr[$index] = $seg;
                }
            }
            $pathArr = $tmpArr;
        }

        $parts['path'] = implode('/', $pathArr);
        $url = $this->buildUrl($parts);

        return redirect()->to($url);
    }

    protected function buildUrl(array $parts)
    {
        $scheme = isset($parts['scheme']) ? ($parts['scheme'] . '://') : '';
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? (':' . $parts['port']) : '';
        $user = $parts['user'] ?? '';
        $pass = isset($parts['pass']) ? (':' . $parts['pass']) : '';
        $pass = ($user || $pass) ? ($pass . '@') : '';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';
        return implode('', [$scheme, $user, $pass, $host, $port, $path, $query, $fragment]);
    }
}
