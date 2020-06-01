<?php

namespace Modules\Page\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Page\Entities\Page;
use Modules\Page\Repositories\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Modules\Core\Http\Controllers\BaseAdminController;

class PageController extends BaseAdminController
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        parent::__construct();
        $this->pageRepository = $pageRepository;
    }

    public function index()
    {
        $this->seo()->setTitle(__('page::page.title.Pages'));
        $this->breadcrumb->addItem(__('page::page.title.Pages'));
        return $this->view('page::admin.page.index');
    }

    public function create()
    {
        $this->seo()->setTitle(__('page::page.title.Create a page'));
        $this->breadcrumb->addItem(__('page::page.title.Pages'), route('admin.page.index'));
        $this->breadcrumb->addItem(__('page::page.title.Create a page'));
        $layouts = $this->getLayouts();
        return $this->view('page::admin.page.create', compact('layouts'));
    }

    public function edit(Page $page)
    {
        if (!$page) {
            return route('admin.page.index');
        }
        $this->seo()->setTitle($page->title);
        $this->breadcrumb->addItem(trans('page::page.title.Pages'), route('admin.page.index'));
        $this->breadcrumb->addItem($page->title);
        $pageContent = $this->getPageContent($page);
        $codeContent = $this->getCodeContent($page);
        $layouts = $this->getLayouts();
        return $this->view('page::admin.page.edit', compact('page', 'pageContent', 'layouts', 'codeContent'));
    }

    public function duplicate(Page $page)
    {
        if (!$page) {
            return route('admin.page.index');
        }
        $this->seo()->setTitle($page->title);
        $this->breadcrumb->addItem(trans('page::page.title.Pages'), route('admin.page.index'));
        $this->breadcrumb->addItem(trans("Duplicate"));
        $pageContent = $this->getPageContent($page);
        $layouts = $this->getLayouts();
        $codeContent = $this->getCodeContent($page);
        return $this->view('page::admin.page.duplicate', compact('page', 'pageContent', 'layouts', 'codeContent'));
    }

    public function store(Request $request)
    {
        $lang = locale();
        $data = $request->all();
        $rules = [
            'status' => 'required'
        ];
        $attributeNames = [
            'template' => __('page::page.labels.Template')
        ];

        $translatedRules = [
            'title' => 'required|string',
            /*'slug' => [
                'required',
                Rule::unique('page_translations', 'slug')
            ],*/
        ];

        $translatedAttributeNames = [
            'title' => 'page::page.labels.Title',
            'body' => 'page::page.labels.Body',
            'slug' => 'Slug',
            'status' => 'page::page.labels.Status',
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
                Rule::unique('page_translations', 'slug')->ignore(Arr::get($data, 'id'), 'page_id')
            ];
        }*/
        if (empty($data[$lang]['slug'])) {
            $data[$lang]['slug'] = Str::slug($data[$lang]['title']);
        }
        $countSlug = \DB::table('page__page_translations')->where('slug', '=', $data[$lang]['slug']);
        if (Arr::get($data, 'id') !== null) {
            $countSlug->where('page_id', '<>', Arr::get($data, 'id'));
        }
        $countSlug = $countSlug->count();
        if ($countSlug) {
            $data[$lang]['slug'] .= "-" . ($countSlug + 1);
        }

        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            if (Arr::get($data, 'id') !== null) {
                return redirect()->route('admin.page.edit', ['page' => Arr::get($data, 'id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        $msg = __('page::page.messages.Page was created!');
        if (Arr::get($data, 'id') === null) {
            $page = $this->pageRepository->create($data);
        } else {
            $page = $this->pageRepository->find($data['id']);
            $this->pageRepository->update($page, $data);
            $msg = __('page::page.messages.Page was updated!');
        }
        $this->savePageContentFile($page, $request->get('page_content'));
        $this->saveCodeContentFile($page, $request->get('code_content'));
        return redirect()->route('admin.page.index')->withSuccess($msg);
    }
    /**
     * Lay noi dung file template
     *
     * @param $filename
     * @return string
     */
    protected function getPageContent($page) {
        if ($page) {
            $filename = "page-".$page->id."-".locale();
            $file = \Theme::path(\Settings::get('website', 'frontend_theme', 'simple')) . '/views/page/'.$filename.'.blade.php';
            if (\File::exists($file)) {
                return \File::get($file);
            }
        }
        return '';
    }

    protected function getCodeContent($page) {
        if ($page) {
            $filename = "page-".$page->id."-".locale();
            $file = \Theme::path(\Settings::get('website', 'frontend_theme', 'simple')) . '/views/page/'.$filename.'.php';
            if (\File::exists($file)) {
                return \File::get($file);
            }
        }
        return '';
    }

    protected function saveCodeContentFile(Page $page, $content)
    {
        $filename = "page-".$page->id."-".locale();
        if (!$page->code_file) {
            $page->update([locale() => ['filename' => $filename]]);
        }
        $file = \Theme::path(\Settings::get('website', 'frontend_theme', 'simple')) . '/views/page/'.$filename.'.php';
        return \File::put($file, $content);
    }

    /**
     * Luu file template
     * @param Page $page
     * @param $content
     * @return int
     */
    protected function savePageContentFile(Page $page, $content)
    {
        $filename = "page-".$page->id."-".locale();
        if (!$page->filename) {
            $page->update([locale() => ['filename' => $filename]]);
        }
        $file = \Theme::path(\Settings::get('website', 'frontend_theme', 'simple')) . '/views/page/'.$filename.'.blade.php';
        return \File::put($file, $content);
    }

    private function getLayouts() {
        $path = \Theme::path(\Settings::get('website', 'frontend_theme', 'simple')) . '/views/layouts';
        $files = \File::files($path);
        $layouts = [];
        foreach($files as $file) {
            $filename = str_replace('.blade.php', '', $file->getFilename());
            if(!in_array($filename, ['install', 'email'])) {
                $layouts[$filename] = $filename;
            }
        }
        return $layouts;
    }
}
