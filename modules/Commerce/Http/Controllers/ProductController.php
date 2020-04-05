<?php

namespace Modules\Commerce\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Commerce\Repositories\CategoryRepository;
use Modules\Commerce\Repositories\ProductRepository;
use Modules\Core\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $productRepository;

    protected $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $request->merge(['status' => 1]);
        $products = $this->productRepository->serverPagingFor($request);
        return $this->view('commerce::product.index', compact('products'));
    }

    /**
     * @param $slug
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category($slug, Request $request)
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if (!$category) {
            abort(404);
        }
        $request->merge(['status' => 1, 'category_id' => $category->id]);
        $products = $this->productRepository->serverPagingFor($request);
        return $this->view('commerce::product.category', compact('products', 'category'));
    }

    /**
     * @param String $slug
     * @return mixed
     */
    public function detail($slug)
    {
        $product = $this->productRepository->findBySlug($slug);
        if (!$product || $product->status !== 1) {
            abort(404);
        }
        return $this->view('commerce::product.detail', compact('product'));
     }
}
