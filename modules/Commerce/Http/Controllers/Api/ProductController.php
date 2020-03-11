<?php

namespace Modules\Commerce\Http\Controllers\Api;

use Modules\Commerce\Entities\Product;
use Modules\Commerce\Transformers\FullProductTransformer;
use Modules\Commerce\Repositories\ProductRepository;
use Modules\Core\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $products = $this->productRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $products->total(),
                "recordsFiltered" => $products->total(),
                'data' => FullProductTransformer::collection($products),
            ];
            return response()->json($output);
        }
        return FullProductTransformer::collection($this->productRepository->serverPagingFor($request));
    }

    public function store()
    {

    }

    public function destroy(Product $product)
    {
        $ok = $this->productRepository->destroy($product);
        return response()->json(['error' => !$ok]);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $products = $this->productRepository->newQueryBuilder()->whereIn('id', $ids)->get();
        foreach ($products as $product) {
            $this->productRepository->destroy($product);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroy($productId)
    {
        $product = $this->productRepository->trashedFind($productId);
        if ($product && $product->trashed()) {
            $this->productRepository->forceDestroy($product);
        }
        return response()->json(['error' => false]);
    }

    public function forceDestroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $products = $this->productRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($products as $product) {
            if ($product->trashed()) {
                $this->productRepository->forceDestroy($product);
            }
        }
        return response()->json(['error' => false]);
    }

    public function restore($productId)
    {
        $product = $this->productRepository->trashedFind($productId);
        if ($product && $product->trashed()) {
            $product->restore();
        }
        return response()->json(['error' => false]);
    }

    public function restoreMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $products = $this->productRepository->newQueryBuilder()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($products as $product) {
            if ($product->trashed()) {
                $product->restore();
            }
        }
        return response()->json(['error' => false]);
    }

    public function toggleStatus(Request $request)
    {
        $id = $request->get('id');
        $product = $this->productRepository->newQueryBuilder()->withTrashed()->find($id);
        if ($product) {
            $product->status = !$product->status;
            $product->save();
        }
        return response()->json(['error' => false]);
    }
}
