<?php

namespace Modules\Testimonial\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Testimonial\Events\TestimonialWasCreated;
use Modules\Testimonial\Events\TestimonialWasDeleting;
use Modules\Testimonial\Events\TestimonialWasUpdated;
use Modules\Testimonial\Entities\Testimonial;
use Illuminate\Http\Request;

class TestimonialRepository extends EloquentBaseRepository implements \Modules\Testimonial\Repositories\TestimonialRepository
{

    public function create($data)
    {
        /** @var Testimonial $post */
        $post = $this->model->create($data);
        event(new TestimonialWasCreated($post, $data));
        return $post;
    }

    public function update($post, $data)
    {
        $post->update($data);

        event(new TestimonialWasUpdated($post, $data));
        return $post;
    }

    public function findDeletedTestimonial($id)
    {
        return $this->newQueryBuilder()->withTrashed()->find($id);
    }

    public function destroy($post)
    {
        /** @var Testimonial $post */
        $post->delete();
    }

    public function forceDestroy($post)
    {
        $post->untag();
        $post->categories()->detach();
        event(new TestimonialWasDeleting($post));
        $post->forceDelete();
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->whereHas('translations', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('position', 'LIKE', "%{$keyword}%")
                    ->orWhere('content', 'LIKE', "%{$keyword}%");
            })->orWhere('id', $keyword);
        }

        if ($request->get('is_deleted') !== null) {
            $query->onlyTrashed();
        }

        if ($request->get('name') !== null) {
            $title = $request->get('name');
            $query->whereHas('translations', function ($q) use ($title) {
                $q->where('name', 'LIKE', "%{$title}%");
            });

        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', '=', $status);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($request->get('group_by') !== null) {
            $query->groupBy(explode(",", $request->get('group_by')));
        }
        return $query->paginate($request->get('per_page', 10));
    }
}
