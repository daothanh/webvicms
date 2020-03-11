<?php

namespace Modules\Blog\Repositories\Eloquent;

use Illuminate\Support\Str;
use \Modules\Core\Repositories\Eloquent\BaseRepository;
use Modules\Blog\Events\PostWasCreated;
use Modules\Blog\Events\PostWasDeleting;
use Modules\Blog\Events\PostWasUpdated;
use Illuminate\Http\Request;

class PostRepository extends BaseRepository implements \Modules\Blog\Repositories\PostRepository
{

    public function create($data)
    {
        $post = $this->model->create($data);
        event(new PostWasCreated($post, $data));
        return $post;
    }

    public function update($post, $data)
    {
        $post->update($data);
        event(new PostWasUpdated($post, $data));
        return $post;
    }

    public function destroy($post)
    {
        //event(new PostWasDeleting($post));
        return $post->delete();
    }

    public function forceDestroy($post)
    {
        event(new PostWasDeleting($post));
        return $post->forceDelete();
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
