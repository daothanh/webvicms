<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Core\Entities\Website;
use Modules\Core\Events\SettingsSaved;
use Modules\Core\Http\Controllers\BaseAdminController;

class SettingsController extends BaseAdminController
{
    public function index()
    {
        $activeTheme = \Theme::info(\Settings::get('website', 'frontend_theme', 'simple'));
        $s = [];
        $s['website'] = \Settings::get('website');
        $s['theme'] = \Settings::get("theme");
        $website = new Website();

        $this->seo()->setTitle(trans('core::settings.title.General settings'));
        $this->breadcrumb->addItem(trans('core::settings.title.General settings'));

        return $this->view('core::admin.settings.index', compact('s', 'activeTheme', 'website'));
    }

    public function store(Request $request)
    {
        $s = $request->get('s');
        foreach($s as $category => $catVals) {
            foreach ($catVals as $k => $v) {
                \Settings::set($category, $k, $v);
            }
        }
        if($request->get('medias_single') !== null) {
            $website = new Website();
            event(new SettingsSaved($website, ['medias_single' => $request->get('medias_single')]));
        }
        return redirect()->route('admin.settings.index')->withSuccess(__('core::settings.messages.Settings were saved!'));
    }

    public function mailServer(Request $request)
    {
        if ($request->isMethod('POST')) {
            $mailservers = $request->get('mailservers');
            foreach($mailservers as $category => $catVals) {
                \Settings::set('mailservers', $category, $catVals);
            }
        }
        $mailservers = \Settings::get('mailservers');

        $this->seo()->setTitle(trans('core::settings.title.Mail Server'));
        $this->breadcrumb->addItem(trans('core::settings.title.Mail Server'));

        return $this->view('core::admin.settings.mail-server', compact('mailservers'));
    }

    public function account(Request $request)
    {
        if ($request->isMethod('POST')) {
            $accounts = $request->get('account');
            foreach($accounts as $category => $catVals) {
                \Settings::set('account', $category, $catVals);
            }
        }
        $account = \Settings::get('account');
        $this->seo()->setTitle(trans('core::settings.title.Account settings'));
        $this->breadcrumb->addItem(trans('core::settings.title.Account settings'));

        return $this->view('core::admin.settings.account', compact('account'));
    }

    public function company(Request $request)
    {
        if ($request->isMethod('POST')) {
            $accounts = $request->get('company');
            foreach($accounts as $category => $catVals) {
                \Settings::set('company', $category, $catVals);
            }
        }
        $company = \Settings::get('company');
        $this->seo()->setTitle(trans('core::settings.title.Company'));
        $this->breadcrumb->addItem(trans('core::settings.title.Company'));

        return $this->view('core::admin.settings.company', compact('company'));
    }

    public function clearCache(Request $request)
    {
        \Artisan::call("cache:clear");
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        \Artisan::call('optimize:clear');
        \Artisan::call('event:clear');
        \Artisan::call('config:clear');

        $this->seo()->setTitle(trans('core::core.Clear cache'));
        $this->breadcrumb->addItem(trans('core::core.Clear cache'));

        return $this->view('core::admin.settings.clear-cache');
    }
}
