<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\BaseAdminController;
use Illuminate\Http\Request;

class LanguageController extends BaseAdminController
{
    public function index()
    {
        $this->seo()->setTitle(trans('Languages'));
        $this->breadcrumb->addItem(trans('Languages'));
        return $this->view('core::admin.languages');
    }
}
