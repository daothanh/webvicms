<?php

namespace Modules\Blog\Http\Controllers\Api;

use Modules\Blog\Transformers\FullPostTransformer;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
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
                'data' => FullPostTransformer::collection($posts),
            ];
            return response()->json($output);
        }
        return FullPostTransformer::collection($this->postRepository->serverPagingFor($request));
    }

    public function destroy($id)
    {
        $post = $this->postRepository->find($id);
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

    public function forceDestroy($id)
    {
        $post = $this->postRepository->trashedFind($id);
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

    public function restore($id)
    {
        $post = $this->postRepository->trashedFind($id);
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
