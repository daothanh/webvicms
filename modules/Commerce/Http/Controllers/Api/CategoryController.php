<?php

namespace Modules\Commerce\Http\Controllers\Api;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

    public function store(Request $request)
    {
        $languages = locales();
        $data = $request->all();
        $rules = [
//            'status' => 'required'
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
            return response()->json($validator->messages(), 400);
        }

        $msg = __('commerce::category.messages.Category was created!');
        if (Arr::get($data, 'id') === null) {
            if (!empty($data['pid'])) {
                $order = $this->categoryRepository
                    ->newQueryBuilder()
                    ->where('pid', '=', $data['pid'])
                    ->count();
                $data['order'] = $order + 1;
            }

            $category = $this->categoryRepository->create($data);
        } else {
            $category = $this->categoryRepository->find($data['id']);
            if (!empty($data['pid']) && $data['pid'] !== $category->pid) {
                $order = $this->categoryRepository
                    ->newQueryBuilder()
                    ->where('pid', '=', $data['pid'])
                    ->count();
                $data['order'] = $order + 1;
            }
            $this->categoryRepository->update($category, $data);
            $msg = __('commerce::category.messages.Category was updated!');
        }
        return response()->json(['success' => $msg]);
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

    public function updatePosition(Request $request)
    {
        $items = $request->get('items');
        if ($items) {
            foreach ($items as $item) {
                $this->categoryRepository
                    ->newQueryBuilder()
                    ->where('id', '=', $item['id'])
                    ->update([
                        'order' => $item['order'],
                        'pid' => $item['pid']
                    ]);
            }
        }
    }
}
