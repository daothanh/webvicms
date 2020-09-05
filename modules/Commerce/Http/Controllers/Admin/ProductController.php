<?php

namespace Modules\Commerce\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Commerce\Entities\Product;
use Modules\Commerce\Repositories\CategoryRepository;
use Modules\Commerce\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseAdminController;

class ProductController extends BaseAdminController
{
    protected $productRepository;
    protected $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $this->seo()->setTitle(__('commerce::product.title.Products'));
        $this->breadcrumb->addItem(__('commerce::product.title.Products'));
        return $this->view('commerce::admin.product.index');
    }

    public function create()
    {
        $this->seo()->setTitle(__('commerce::product.title.Create a product'));
        $this->breadcrumb->addItem(__('commerce::product.title.Products'), route('admin.commerce.product.index'));
        $this->breadcrumb->addItem(__('commerce::product.title.Create a product'));

        $categories = $this->categoryRepository->getCategories(true);
        return $this->view('commerce::admin.product.create', compact('categories'));
    }

    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return route('admin.commerce.product.index');
        }
        $this->seo()->setTitle($product->title);
        $this->breadcrumb->addItem(trans('commerce::product.title.Products'), route('admin.commerce.product.index'));
        $this->breadcrumb->addItem($product->title);
        $categories = $this->categoryRepository->getCategories();
        return $this->view('commerce::admin.product.edit', compact('product', 'categories'));
    }

    public function duplicate($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return route('admin.commerce.product.index');
        }
        $this->seo()->setTitle($product->title);
        $this->breadcrumb->addItem(trans('commerce::product.title.Products'), route('admin.commerce.product.index'));
        $this->breadcrumb->addItem(trans("Duplicate"));
        $categories = $this->categoryRepository->getCategories(true);
        return $this->view('commerce::admin.product.duplicate', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $languages = locales();
        $data = $request->all();
        $rules = [
            'status' => 'required'
        ];
        $attributeNames = [
            'template' => __('commerce::product.labels.Template')
        ];

        $translatedRules = [
            'title' => 'required|string',
            /*'slug' => [
                'required',
                Rule::unique('product_translations', 'slug')
            ],*/
        ];

        $translatedAttributeNames = [
            'title' => 'commerce::product.labels.Title',
            'body' => 'commerce::product.labels.Body',
            'slug' => 'Slug',
            'status' => 'commerce::product.labels.Status',
        ];
        foreach ($translatedRules as $ruleKey => $rule) {
            foreach ($languages as $lang) {
                $rules["{$lang}.{$ruleKey}"] = $rule;
            }
        }

        foreach ($translatedAttributeNames as $attributeKey => $attributeName) {
            foreach ($languages as $lang) {
                $attributeNames["{$lang}.{$attributeKey}"] = __($attributeName, [], $lang);
            }
        }

        /*if (Arr::get($data, 'id') !== null) {
            $rules[$locale . '.slug'] = [
                'required',
                Rule::unique('product_translations', 'slug')->ignore(Arr::get($data, 'id'), 'product_id')
            ];
        }*/
        foreach ($languages as $lang) {
            if (empty($data[$lang]['slug'])) {
                $data[$lang]['slug'] = Str::slug($data[$lang]['title']);
            }
            $countSlug = \DB::table('commerce__product_translations')->where('slug', '=', $data[$lang]['slug']);
            if (Arr::get($data, 'id') !== null) {
                $countSlug->where('product_id', '<>', Arr::get($data, 'id'));
            }
            $countSlug = $countSlug->count();
            if ($countSlug) {
                $data[$lang]['slug'] .= "-" . ($countSlug + 1);
            }
        }

        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            if (Arr::get($data, 'id') !== null) {
                return redirect()->route('admin.commerce.product.edit', ['id' => Arr::get($data, 'id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $msg = __('commerce::product.messages.Product was created!');
        if (Arr::get($data, 'id') === null) {
            $product = $this->productRepository->create($data);
        } else {
            $product = $this->productRepository->find($data['id']);
            $this->productRepository->update($product, $data);
            $msg = __('commerce::product.messages.Product was updated!');
        }
        return redirect()->route('admin.commerce.product.index')->withSuccess($msg);
    }
}
