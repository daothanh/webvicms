<?php

namespace Modules\Slider\Repositories\Eloquent;

use \Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Slider\Events\SliderItemWasCreated;
use Modules\Slider\Events\SliderItemWasDeleting;
use Modules\Slider\Events\SliderItemWasUpdated;
use Illuminate\Http\Request;

class EloquentSliderRepository extends EloquentBaseRepository implements \Modules\Slider\Repositories\SliderRepository
{

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('title') !== null) {
            $name = $request->get('title');
            $query->where('title', 'LIKE', "%{$name}%");
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
