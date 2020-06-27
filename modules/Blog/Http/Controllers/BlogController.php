<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\Entities\Post;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Http\Controllers\Controller;

class BlogController extends Controller
{
    /**
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    public function __construct(PostRepository $postRepository, CategoryRepository $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $request->merge(['status' => 1]);
        $posts = $this->postRepository->serverPagingFor($request);
        $this->seo()->setTitle(__('blog::post.title.Posts'));
        return $this->view('blog::index', compact('posts'));
    }

    public function detail(Request $request, $slug)
    {
        /** @var Post $post */
        $post = $this->postRepository->findBySlug($slug);
        if (!$post || $post->status !== 1) {
            abort(404);
        }
        $params = ['status' => 1, 'locale' => locale()];
        if ($post->categories->isNotEmpty())
        {
            $params += ['category_ids' => $post->categories->pluck('id')->toArray()];
        }
        $request->merge($params);
        $relatedPosts = $this->postRepository->serverPagingFor($request);
        $this->seo()->setTitle($post->title);
        $this->seo()->setDescription($post->excerpt);
        return $this->view('blog::detail', compact('post', 'relatedPosts'));
    }

    public function category($slug, Request $request)
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if (!$category || $category->status !== 1) {
            abort(404);
        }
        $request->merge(['status' => 1, 'category_id' => $category->id, 'locale' => locale()]);
        $posts = $this->postRepository->serverPagingFor($request);

        $this->seo()->setTitle($category->name);
        $this->seo()->setDescription($category->body);
        return $this->view('blog::category', compact('category', 'posts'));
    }
}
