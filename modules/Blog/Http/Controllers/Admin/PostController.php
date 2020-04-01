<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Blog\Repositories\PostRepository;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseAdminController;

class PostController extends BaseAdminController
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        parent::__construct();
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        $this->seo()->setTitle(__('blog::post.title.Posts'));
        $this->breadcrumb->addItem(__('blog::post.title.Posts'));
        return $this->view('blog::admin.post.index');
    }

    public function create()
    {
        $this->seo()->setTitle(__('blog::post.title.Create a post'));
        $this->breadcrumb->addItem(__('blog::post.title.Posts'), route('admin.blog.post.index'));
        $this->breadcrumb->addItem(__('blog::post.title.Create a post'));
        return $this->view('blog::admin.post.create');
    }

    public function edit($id)
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            return route('admin.blog.post.index');
        }
        $this->seo()->setTitle($post->title);
        $this->breadcrumb->addItem(trans('blog::post.title.Posts'), route('admin.blog.post.index'));
        $this->breadcrumb->addItem($post->title);
        return $this->view('blog::admin.post.edit', compact('post'));
    }

    public function duplicate($id)
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            return route('admin.blog.post.index');
        }
        $this->seo()->setTitle($post->title);
        $this->breadcrumb->addItem(trans('blog::post.title.Posts'), route('admin.blog.post.index'));
        $this->breadcrumb->addItem(trans("Duplicate"));
        return $this->view('blog::admin.post.duplicate', compact('post'));
    }

    public function store(Request $request)
    {
        $languages = locales();
        $data = $request->all();
        $rules = [
            'status' => 'required'
        ];
        $attributeNames = [
            'template' => __('blog::post.labels.Template')
        ];

        $translatedRules = [
            'title' => 'required|string',
            /*'slug' => [
                'required',
                Rule::unique('post_translations', 'slug')
            ],*/
        ];

        $translatedAttributeNames = [
            'title' => 'blog::post.labels.Title',
            'body' => 'blog::post.labels.Body',
            'slug' => 'Slug',
            'status' => 'blog::post.labels.Status',
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
                Rule::unique('post_translations', 'slug')->ignore(Arr::get($data, 'id'), 'post_id')
            ];
        }*/
        foreach ($languages as $lang) {
            if (empty($data[$lang]['slug'])) {
                $data[$lang]['slug'] = Str::slug($data[$lang]['title']);
            }
            $countSlug = \DB::table('blog__post_translations')->where('slug', '=', $data[$lang]['slug']);
            if (Arr::get($data, 'id') !== null) {
                $countSlug->where('post_id', '<>', Arr::get($data, 'id'));
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
                return redirect()->route('admin.blog.post.edit', ['post' => Arr::get($data, 'id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        $msg = __('blog::post.messages.Post was created!');
        if (Arr::get($data, 'id') === null) {
            $post = $this->postRepository->create($data);
        } else {
            $post = $this->postRepository->find($data['id']);
            $this->postRepository->update($post, $data);
            $msg = __('blog::post.messages.Post was updated!');
        }
        return redirect()->route('admin.blog.post.index')->withSuccess($msg);
    }
}
