<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Emails\ContactEmail;
use Modules\Core\Http\Requests\ContactRequest;
use Modules\User\Entities\User;

class HomeController extends Controller
{
    public function index()
    {
        if (env('APP_INSTALLED') !== true) {
            return redirect()->route('install.app');
        }
        if (class_exists('Modules\Page\Providers\PageServiceProvider')) {
            $page = app(\Modules\Page\Repositories\PageRepository::class)->newQueryBuilder()
                ->with(['translations'])
                ->where('is_home', '=', 1)
                ->where('status','=', 1)
                ->first();
            if ($page) {
                if(\Request::url() !== $page->getUrl()) {
                    return redirect()->to($page->getUrl(), 301);
                }
                $this->seo()->setTitle($page->title ?? site_name());
                $this->seo()->setCanonical($page->getUrl() ?? '/');
                $this->seo()->setDescription(str_limit(clean_html($page->body)) ?? site_description());
                if ($page->featuredImage) {
                    $this->seo()->addImages($page->featuredImage->path->getUrl());
                } else {
                    $this->seo()->addImages(get_logo());
                }
                return $this->view('page::page.'.($page->layout ?? 'default'), compact('page'));
            }
        }
        $this->seo()->setTitle(site_name());
        $this->seo()->setDescription(site_description());
        $this->seo()->metatags()->setKeywords(site_keywords());
        $this->seo()->addImages(get_logo());

        return $this->view('home');
    }

    public function contact()
    {
        $company = settings('company');
        $this->seo()->setTitle(trans('vcch::contact.title'));
        return $this->view('core::contact', compact('company'));
    }

    public function contactSend(ContactRequest $request)
    {
        $data = $request->all();
        $adminEmails = User::query()->whereHas('roles', function ($q) {
            $q->where('name', '=', 'admin');
        })->get()->pluck('email');
        if ($adminEmails) {
            foreach ($adminEmails as $adminEmail) {
                \Mail::to($adminEmail)->send(new ContactEmail($data));
            }
        }

        if (\Arr::get($data, 'email')) {
            \Mail::to($data['email'])->send(new ContactEmail($data));
        }
        return redirect()->route('contact')->withSuccess(trans('vcch::contact.send_success'));
    }
}
