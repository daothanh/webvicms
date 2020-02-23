<?php
namespace Modules\Page\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Modules\Page\Repositories\PageRepository;

class PageController extends Controller
{
    protected $page;
    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    public function detail($slug)
    {
        $page = $this->page->findBySlug($slug);
        if (!$page) {
            abort(404);
        }
        if (\Request::url() !== $page->getUrl()) {
            return redirect()->to($page->getUrl(), 301);
        }

        $seo = $page->seoByLocale(locale());
        if ($seo) {
            $this->seo()->setTitle($seo->title);
            $this->seo()->setDescription(clean_html($seo->description));
            $this->seo()->metatags()->setKeywords(explode(',', $seo->keywords));
        } else {
            $this->seo()->setTitle($page->title);
            $this->seo()->setDescription(str_limit(clean_html($page->body)));
        }
        $this->seo()->setCanonical($page->getUrl());

        if ($page->featuredImage) {
            $this->seo()->addImages($page->featuredImage->path->getUrl());
        }
        return $this->view('page.'.($page->layout ?? 'default'), compact('page'));
    }
}
