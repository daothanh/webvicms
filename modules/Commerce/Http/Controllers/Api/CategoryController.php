<?php

namespace Modules\Commerce\Http\Controllers\Api;

use Modules\Commerce\Entities\Category;
use Modules\Commerce\Transformers\FullCategoryTransformer;
use Modules\Commerce\Repositories\CategoryRepository;
use Modules\Core\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $categories = $this->categoryRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $categories->total(),
                "recordsFiltered" => $categories->total(),
                'data' => FullCategoryTransformer::collection($categories),
            ];
            return response()->json($output);
        }
        return FullCategoryTransformer::collection($this->categoryRepository->serverPagingFor($request));
    }

    public function store()
    {

    }

    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);
        $ok = $this->categoryRepository->destroy($category);
        return response()->json(['error' => !$ok]);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $categories = $this->categoryRepository->newQueryBuilder()->whereIn('id', $ids)->get();
        foreach ($categories as $category) {
            $this->categoryRepository->destroy($category);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroy($id)
    {
        $category = $this->categoryRepository->trashedFind($id);
        if ($category && $category->trashed()) {
            $this->categoryRepository->forceDestroy($category);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $categories = $this->categoryRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($categories as $category) {
            if ($category->trashed()) {
                $this->categoryRepository->forceDestroy($category);
            }
        }
        return response()->json(['error' => false]);
    }

    public function restore($id)
    {
        $category = $this->categoryRepository->trashedFind($id);
        if ($category && $category->trashed()) {
            $category->restore();
        }
        return response()->json(['error' => false]);
    }

    public function restoreMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $categories = $this->categoryRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($categories as $category) {
            if ($category->trashed()) {
                $category->restore();
            }
        }
        return response()->json(['error' => false]);
    }

    public function toggleStatus(Request $request)
    {
        $id = $request->get('id');
        $category = $this->categoryRepository->newQueryBuilder()->withTrashed()->find($id);
        $error = false;
        if ($category) {
            $category->status = !$category->status;
            $category->save();
        } else {
          $error = true;
        }
        return response()->json(['error' => $error]);
    }
}
