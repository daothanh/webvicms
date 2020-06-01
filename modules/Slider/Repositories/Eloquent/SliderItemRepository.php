<?php

namespace Modules\Slider\Repositories\Eloquent;

use \Modules\Core\Repositories\Eloquent\BaseRepository;
use Modules\Slider\Events\SliderItemWasCreated;
use Modules\Slider\Events\SliderItemWasDeleting;
use Modules\Slider\Events\SliderItemWasUpdated;
use Illuminate\Http\Request;

class SliderItemRepository extends BaseRepository implements \Modules\Slider\Repositories\SliderItemRepository
{

    public function create($data)
    {
        $sliderItem = $this->model->create($data);
        event(new SliderItemWasCreated($sliderItem, $data));
        return $sliderItem;
    }

    public function update($sliderItem, $data)
    {
        $sliderItem->update($data);
        event(new SliderItemWasUpdated($sliderItem, $data));
        return $sliderItem;
    }

    public function destroy($sliderItem)
    {
        event(new SliderItemWasDeleting($sliderItem));
        return $sliderItem->delete();
    }

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

        if ($request->get('slider_id') !== null) {
            $sliderId = $request->get('slider_id');
            $query->where('slider_id', '=', $sliderId);
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
