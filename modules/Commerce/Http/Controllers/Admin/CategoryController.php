<?php

namespace Modules\Commerce\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Commerce\Entities\Category;
use Modules\Commerce\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseAdminController;

class CategoryController extends BaseAdminController
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $this->seo()->setTitle(__('commerce::category.title.Categories'));
        $this->breadcrumb->addItem(__('commerce::category.title.Categories'));

        $items = $this->categoryRepository->getTree();
        $categories = array_map(function ($item) {
            $item->name = ($item->depth ? str_repeat("-", $item->depth) . " " : '') . $item->name;
            return $item;
        }, $items);

        return $this->view('commerce::admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->getCategories();
        $this->seo()->setTitle(__('commerce::category.title.Create a category'));
        $this->breadcrumb->addItem(__('commerce::category.title.Categories'), route('admin.commerce.category.index'));
        $this->breadcrumb->addItem(__('commerce::category.title.Create a category'));
        return $this->view('commerce::admin.category.create', compact('categories'));
    }

    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return route('admin.commerce.category.index');
        }
        $categories = $this->categoryRepository->getCategories();
        $this->seo()->setTitle($category->title);
        $this->breadcrumb->addItem(trans('commerce::category.title.Categories'), route('admin.commerce.category.index'));
        $this->breadcrumb->addItem($category->title);
        return $this->view('commerce::admin.category.edit', compact('category', 'categories'));
    }

    public function duplicate($id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return route('admin.commerce.category.index');
        }
        $this->seo()->setTitle($category->title);
        $this->breadcrumb->addItem(trans('commerce::category.title.Categories'), route('admin.commerce.category.index'));
        $this->breadcrumb->addItem(trans("Duplicate"));
        return $this->view('commerce::admin.category.duplicate', compact('category'));
    }

    public function store(Request $request)
    {
        $languages = locales();
        $data = $request->all();
        $rules = [
            'status' => 'required'
        ];
        $attributeNames = [
            'template' => __('commerce::category.labels.Template')
        ];

        $translatedRules = [
            'name' => 'required|string',
            /*'slug' => [
                'required',
                Rule::unique('category_translations', 'slug')
            ],*/
        ];

        $translatedAttributeNames = [
            'name' => 'commerce::category.labels.Title',
            'body' => 'commerce::category.labels.Body',
            'slug' => 'Slug',
            'status' => 'commerce::category.labels.Status',
        ];

        foreach ($translatedRules as $ruleKey => $rule) {
            foreach ($languages as $lang) {
                $rules["{$lang}.{$ruleKey}"] = $rule;
            }
        }

        foreach ($translatedAttributeNames as $attributeKey => $attributeName) {
            foreach ($languages as $lang) {
                $attributeNames["{$lang}.{$attributeKey}"] = trans($attributeName, [], $lang);
            }
        }

        /*if (Arr::get($data, 'id') !== null) {
            $rules[$locale . '.slug'] = [
                'required',
                Rule::unique('category_translations', 'slug')->ignore(Arr::get($data, 'id'), 'category_id')
            ];
        }*/
        foreach ($languages as $lang) {
            if (empty($data[$lang]['slug'])) {
                $data[$lang]['slug'] = Str::slug($data[$lang]['name']);
            }
            $countSlug = \DB::table('commerce__category_translations')->where('slug', '=', $data[$lang]['slug']);
            if (Arr::get($data, 'id') !== null) {
                $countSlug->where('category_id', '<>', Arr::get($data, 'id'));
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
                return redirect()->route('admin.commerce.category.edit', ['category' => Arr::get($data, 'id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        $msg = __('commerce::category.messages.Category was created!');
        if (Arr::get($data, 'id') === null) {
            $category = $this->categoryRepository->create($data);
        } else {
            $category = $this->categoryRepository->find($data['id']);
            $this->categoryRepository->update($category, $data);
            $msg = __('commerce::category.messages.Category was updated!');
        }
        return redirect()->route('admin.commerce.category.index')->withSuccess($msg);
    }
}
