<?php

namespace Modules\Page\Repositories\Eloquent;

use Illuminate\Support\Str;
use \Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Page\Entities\Page;
use Modules\Page\Events\PageWasCreated;
use Modules\Page\Events\PageWasDeleting;
use Modules\Page\Events\PageWasUpdated;
use Illuminate\Http\Request;

class EloquentPageRepository extends EloquentBaseRepository implements \Modules\Page\Repositories\PageRepository
{

    public function create($data)
    {
        $page = $this->model->create($data);
        event(new PageWasCreated($page, $data));
        $this->createOrUpdateCustomFields($page, $data);
        return $page;
    }

    public function update($page, $data)
    {
        $page->update($data);
        event(new PageWasUpdated($page, $data));
        $this->createOrUpdateCustomFields($page, $data);
        return $page;
    }

    public function destroy($page)
    {
        if (!$page->is_can_delete) {
            return false;
        }
        //event(new PageWasDeleting($page));
        return $page->delete();
    }

    public function forceDestroy($page)
    {
        event(new PageWasDeleting($page));
        if ($page->customFields->isNotEmpty()) {
            foreach($page->customFields as $cf) {
                $cf->delete();
            }
        }
        return $page->forceDelete();
    }

    public function trashedFind($id)
    {
        return $this->newQueryBuilder()->withTrashed()->find($id);
    }

    public function createOrUpdateCustomFields(Page $page, $data) {
        if (!empty($data['custom_fields']) && is_array($data['custom_fields'])) {
            $existIds = [];
            foreach($data['custom_fields'] as $cField) {
                if (isset($cField['id'])) {
                    $field = $page->customFields()->find($cField['id']);
                    if ($field) {
                        $field->update($cField);
                    }
                } else {
                    $field = $page->customFields()->create($cField);
                }
                $existIds[] = $field->id;
            }
            foreach ($page->customFields as $pField) {
                if (!in_array($pField->id, $existIds)) {
                    $pField->delete();
                }
            }
        }
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
            $query->whereHas('translations', function ($query) use ($status) {
                $query->where('status', '=', $status);
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            if (Str::contains($request->get('order_by'), '.')) {
                $fields = explode('.', $request->get('order_by'));

                $query->with('translations')->join('page_translations as t', function ($join) {
                    $join->on('pages.id', '=', 't.page_id');
                })
                    ->where('t.locale', \App::getLocale())
                    ->groupBy('pages.id')->orderBy("t.{$fields[1]}", $order);
            } else {
                $query->orderBy($request->get('order_by'), $order);
            }
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
