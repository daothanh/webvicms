<?php
namespace Modules\Tag\Repositories\Eloquent;

use \Modules\Core\Repositories\Eloquent\BaseRepository;
use Modules\Tag\Events\TagIsCreating;
use Modules\Tag\Events\TagIsUpdating;
use Modules\Tag\Events\TagWasCreated;
use Modules\Tag\Events\TagWasUpdated;
use Illuminate\Http\Request;

class TagRepository extends BaseRepository implements \Modules\Tag\Repositories\TagRepository {

    /**
     * Get all the tags in the given namespace
     * @param string $namespace
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allForNamespace($namespace)
    {
        return $this->model->where('namespace', $namespace)->get();
    }

    public function create($data)
    {
        event($event = new TagIsCreating($data));
        $tag = $this->model->create($event->getAttributes());

        event(new TagWasCreated($tag));

        return $tag;
    }

    public function update($tag, $data)
    {
        event($event = new TagIsUpdating($tag, $data));
        $tag->update($event->getAttributes());

        event(new TagWasUpdated($tag));

        return $tag;
    }

    public function findBySlug($slug)
    {
        return $this->newQueryBuilder()->where('slug', '=', $slug)->first();
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('namespace', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('name') !== null) {
            $name = $request->get('name');
            $query->where('name', 'LIKE', "%{$name}%");
        }

        if ($request->get('slug') !== null) {
            $slug = $request->get('slug');
            $query->where('slug', '=', $slug);
        }

        if ($request->get('namespace') !== null) {
            $namespace = $request->get('namespace');
            $query->where('namespace', '=', $namespace);
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
