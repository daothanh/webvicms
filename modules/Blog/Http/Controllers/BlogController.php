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
        return $this->view('blog::index', compact('posts'));
    }

    public function detail($slug)
    {
        /** @var Post $post */
        $post = $this->postRepository->findBySlug($slug);
        if (!$post || $post->status !== 1) {
            abort(404);
        }
        return $this->view('blog::detail', compact('post'));
    }

    public function category($slug, Request $request)
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if (!$category || $category->status !== 1) {
            abort(404);
        }
        $request->merge(['status' => 1, 'category_id' => $category->id]);
        $posts = $this->postRepository->serverPagingFor($request);
        return $this->view('blog::category', compact('category', 'posts'));
    }
}
