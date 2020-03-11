<?php

namespace Modules\Blog\Http\Controllers\Api;

use Modules\Blog\Entities\Category;
use Modules\Blog\Transformers\FullCategoryTransformer;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Core\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    protected $postRepository;

    public function __construct(CategoryRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $posts = $this->postRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $posts->total(),
                "recordsFiltered" => $posts->total(),
                'data' => FullCategoryTransformer::collection($posts),
            ];
            return response()->json($output);
        }
        return FullCategoryTransformer::collection($this->postRepository->serverPagingFor($request));
    }

    public function store()
    {

    }

    public function destroy(Category $post)
    {
        $ok = $this->postRepository->destroy($post);
        return response()->json(['error' => !$ok]);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $posts = $this->postRepository->newQueryBuilder()->whereIn('id', $ids)->get();
        foreach ($posts as $post) {
            $this->postRepository->destroy($post);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroy($postId)
    {
        $post = $this->postRepository->trashedFind($postId);
        if ($post && $post->trashed()) {
            $this->postRepository->forceDestroy($post);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $posts = $this->postRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($posts as $post) {
            if ($post->trashed()) {
                $this->postRepository->forceDestroy($post);
            }
        }
        return response()->json(['error' => false]);
    }

    public function restore($postId)
    {
        $post = $this->postRepository->trashedFind($postId);
        if ($post && $post->trashed()) {
            $post->restore();
        }
        return response()->json(['error' => false]);
    }

    public function restoreMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $posts = $this->postRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($posts as $post) {
            if ($post->trashed()) {
                $post->restore();
            }
        }
        return response()->json(['error' => false]);
    }

    public function toggleStatus(Request $request)
    {
        $id = $request->get('id');
        $post = $this->postRepository->newQueryBuilder()->withTrashed()->find($id);
        if ($post) {
            $post->status = !$post->status;
            $post->save();
        }
        return response()->json(['error' => false]);
    }
}
