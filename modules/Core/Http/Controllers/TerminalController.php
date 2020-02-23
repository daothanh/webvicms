<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;

class TerminalController extends Controller
{
    public function index(Request $request)
    {
        $cmd = $request->get('cmd');
        $rs = null;
        $realCmd = $cmd;
        if (strpos($cmd, " ")) {
            $realCmd = substr($cmd, 0, strpos($cmd, " "));
        }
//        dd($realCmd, $cmd);
        if ($cmd && \Arr::has(\Artisan::all(), $realCmd)) {
            \Artisan::call($cmd);
            $rs = \Artisan::output();
            $rs = preg_replace('/\n/', '<br/>', $rs);
            $rs = preg_replace('/\n\r/', '<br/>', $rs);
        }
        return $this->view('core::terminal', compact('rs', 'cmd'));
    }
}
