<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Set default language for site
     *
     * @param $newLocale
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLocale($newLocale, Request $request)
    {
        $url = $request->header('referer', '/');
        $parts = parse_url($url);
        $pathArr = explode('/', $parts['path']);
        $pathArr[1] = $newLocale;
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
