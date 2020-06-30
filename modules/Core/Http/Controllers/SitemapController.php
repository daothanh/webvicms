<?php


namespace Modules\Core\Http\Controllers;


use Modules\Blog\Entities\Post;
use Modules\Page\Entities\Page;
use Spatie\Sitemap\Sitemap as SitemapAlias;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends \Illuminate\Routing\Controller
{
    public function index() {
        $sitemap = SitemapAlias::create()
            ->add(route('home'));

        $pages = Page::where('status', '=', 1)->get();
        foreach ($pages as $page) {
            $url = Url::create($page->getUrl())->setLastModificationDate($page->updated_at);
            $sitemap->add($url);
        }

        $posts = Post::where('status', '=', 1)->get();
        foreach ($posts as $post) {
            $url = Url::create($post->getUrl())->setLastModificationDate($post->updated_at);
            $sitemap->add($url);
        }

        return response($sitemap->render(), 200, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
