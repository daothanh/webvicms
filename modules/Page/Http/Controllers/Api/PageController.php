<?php

namespace Modules\Page\Http\Controllers\Api;

use Modules\Page\Entities\Page;
use Modules\Page\Transformers\FullPageTransformer;
use Modules\Page\Repositories\PageRepository;
use Modules\Core\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class PageController extends ApiController
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $pages = $this->pageRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $pages->total(),
                "recordsFiltered" => $pages->total(),
                'data' => FullPageTransformer::collection($pages),
            ];
            return response()->json($output);
        }
        return FullPageTransformer::collection($this->pageRepository->serverPagingFor($request));
    }

    public function store()
    {

    }

    public function destroy(Page $page)
    {
        $ok = $this->pageRepository->destroy($page);
        return response()->json(['error' => !$ok]);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $pages = $this->pageRepository->newQueryBuilder()->whereIn('id', $ids)->get();
        foreach ($pages as $page) {
            $this->pageRepository->destroy($page);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroy($pageId)
    {
        $page = $this->pageRepository->trashedFind($pageId);
        if ($page && $page->trashed()) {
            $this->pageRepository->forceDestroy($page);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $pages = $this->pageRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($pages as $page) {
            if ($page->trashed()) {
                $this->pageRepository->forceDestroy($page);
            }
        }
        return response()->json(['error' => false]);
    }

    public function restore($pageId)
    {
        $page = $this->pageRepository->trashedFind($pageId);
        if ($page && $page->trashed()) {
            $page->restore();
        }
        return response()->json(['error' => false]);
    }

    public function restoreMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $pages = $this->pageRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($pages as $page) {
            if ($page->trashed()) {
                $page->restore();
            }
        }
        return response()->json(['error' => false]);
    }

    public function toggleStatus(Request $request)
    {
        $id = $request->get('id');
        $page = $this->pageRepository->newQueryBuilder()->withTrashed()->find($id);
        if ($page) {
            $page->status = !$page->status;
            $page->save();
        }
        return response()->json(['error' => false]);
    }
}
