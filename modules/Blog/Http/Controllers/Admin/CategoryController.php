<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Blog\Repositories\CategoryRepository;
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
        $this->seo()->setTitle(__('blog::category.title.Categories'));
        $this->breadcrumb->addItem(__('blog::category.title.Categories'));
        $categories = $this->categoryRepository->getTree();
        return $this->view('blog::admin.category.index', compact('categories'));
    }

    public function create()
    {
        $this->seo()->setTitle(__('blog::category.title.Create a category'));
        $this->breadcrumb->addItem(__('blog::category.title.Categories'), route('admin.blog.category.index'));
        $this->breadcrumb->addItem(__('blog::category.title.Create a category'));
        $categories = $this->categoryRepository->getCategories();
        return $this->view('blog::admin.category.create', compact('categories'));
    }

    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return route('admin.blog.category.index');
        }
        $this->seo()->setTitle($category->title);
        $this->breadcrumb->addItem(trans('blog::category.title.Categories'), route('admin.blog.category.index'));
        $this->breadcrumb->addItem($category->title);
        $categories = $this->categoryRepository->getCategories();
        return $this->view('blog::admin.category.edit', compact('category', 'categories'));
    }

    public function duplicate($id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return route('admin.blog.category.index');
        }
        $this->seo()->setTitle($category->title);
        $this->breadcrumb->addItem(trans('blog::category.title.Categories'), route('admin.blog.category.index'));
        $this->breadcrumb->addItem(trans("Duplicate"));
        return $this->view('blog::admin.category.duplicate', compact('category'));
    }

    public function store(Request $request)
    {
        $lang = locale();
        $data = $request->all();
        $rules = [
            'status' => 'required'
        ];
        $attributeNames = [
            'template' => __('blog::category.labels.Template')
        ];

        $translatedRules = [
            'name' => 'required|string',
            /*'slug' => [
                'required',
                Rule::unique('category_translations', 'slug')
            ],*/
        ];

        $translatedAttributeNames = [
            'name' => 'blog::category.labels.Title',
            'body' => 'blog::category.labels.Body',
            'slug' => 'Slug',
            'status' => 'blog::category.labels.Status',
        ];

        foreach ($translatedRules as $ruleKey => $rule) {
            $rules["{$lang}.{$ruleKey}"] = $rule;
        }

        foreach ($translatedAttributeNames as $attributeKey => $attributeName) {
            $attributeNames["{$lang}.{$attributeKey}"] = trans($attributeName, [], $lang);
        }

        /*if (Arr::get($data, 'id') !== null) {
            $rules[$locale . '.slug'] = [
                'required',
                Rule::unique('category_translations', 'slug')->ignore(Arr::get($data, 'id'), 'category_id')
            ];
        }*/
        if (empty($data[$lang]['slug'])) {
            $data[$lang]['slug'] = Str::slug($data[$lang]['name']);
        }
        $countSlug = \DB::table('blog__category_translations')->where('slug', '=', $data[$lang]['slug']);
        if (Arr::get($data, 'id') !== null) {
            $countSlug->where('category_id', '<>', Arr::get($data, 'id'));
        }
        $countSlug = $countSlug->count();
        if ($countSlug) {
            $data[$lang]['slug'] .= "-" . ($countSlug + 1);
        }

        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            if (Arr::get($data, 'id') !== null) {
                return redirect()->route('admin.blog.category.edit', ['category' => Arr::get($data, 'id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        $msg = __('blog::category.messages.Category was created!');
        if (Arr::get($data, 'id') === null) {
            $category = $this->categoryRepository->create($data);
        } else {
            $category = $this->categoryRepository->find($data['id']);
            $this->categoryRepository->update($category, $data);
            $msg = __('blog::category.messages.Category was updated!');
        }
        return redirect()->route('admin.blog.category.index')->withSuccess($msg);
    }
}
