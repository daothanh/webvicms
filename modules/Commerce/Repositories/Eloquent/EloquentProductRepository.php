<?php

namespace Modules\Commerce\Repositories\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use \Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Commerce\Events\ProductWasCreated;
use Modules\Commerce\Events\ProductWasDeleting;
use Modules\Commerce\Events\ProductWasUpdated;
use Illuminate\Http\Request;

class EloquentProductRepository extends EloquentBaseRepository implements \Modules\Commerce\Repositories\ProductRepository
{

    public function create($data)
    {
        $product = $this->model->create($data);
        $categoryIds = Arr::get($data, 'category_ids');
        if ($categoryIds !== null && is_array($categoryIds)) {
            $product->categories()->sync($categoryIds);
        }
        event(new ProductWasCreated($product, $data));
        return $product;
    }

    public function update($product, $data)
    {
        $product->update($data);
        $categoryIds = Arr::get($data, 'category_ids');
        if ($categoryIds !== null && is_array($categoryIds)) {
            $product->categories()->sync($categoryIds);
        }
        event(new ProductWasUpdated($product, $data));
        return $product;
    }

    public function destroy($product)
    {
        //event(new ProductWasDeleting($product));
        return $product->delete();
    }

    public function forceDestroy($product)
    {
        event(new ProductWasDeleting($product));
        $product->categories()->detach();
        return $product->forceDelete();
    }

    public function trashedFind($id)
    {
        return $this->newQueryBuilder()->withTrashed()->find($id);
    }

    public function findBySlug($slug)
    {
        return $this->newQueryBuilder()->whereHas('translations', function ($query) use ($slug) {
            $query->where('slug', '=', $slug);
        })->first();
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($query) use ($keyword) {
                $query->whereHas('translations', function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%{$keyword}%");
                    $query->orWhere('slug', 'LIKE', "%{$keyword}%");
                })
                    ->orWhere('id', $keyword);
            });
        }

        if ($request->get('is_trashed') !== null) {
            $query->onlyTrashed();
        }

        if ($request->get('title') !== null) {
            $name = $request->get('title');
            $query->whereHas('translations', function ($query) use ($name) {
                $query->where('title', 'LIKE', "%{$name}%");
            });
        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', '=', $status);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            if (Str::contains($request->get('order_by'), '.')) {
                $fields = explode('.', $request->get('order_by'));

                $query->with('translations')->join('commerce__product_translations as t', function ($join) {
                    $join->on('commerce__products.id', '=', 't.product_id');
                })
                    ->where('t.locale', \App::getLocale())
                    ->groupBy('commerce__products.id')->orderBy("t.{$fields[1]}", $order);
            } else {
                $query->orderBy($request->get('order_by'), $order);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
