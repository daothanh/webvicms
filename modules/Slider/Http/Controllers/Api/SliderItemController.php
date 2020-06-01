<?php
namespace Modules\Slider\Http\Controllers\Api;

use Modules\Slider\Entities\Slider;
use Modules\Slider\Entities\SliderItem;
use Modules\Slider\Transformers\SliderTransformer;
use Modules\Slider\Transformers\FullSliderItemTransformer;
use Modules\Slider\Repositories\SliderItemRepository;
use Modules\Core\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class SliderItemController extends ApiController
{
    protected $sliderItemRepository;

    public function __construct(SliderItemRepository $sliderItemRepository)
    {
        $this->sliderItemRepository = $sliderItemRepository;
    }

    public function index(Slider $slider, Request $request)
    {
        $request->merge(['slider_id' => $slider->id]);
        if ($request->get('columns') !== null) {
            // For datatables.net
            $pages  = $this->sliderItemRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw"            => $request->get('draw'),
                "recordsTotal"    => $pages->total(),
                "recordsFiltered" => $pages->total(),
                'data'            => FullSliderItemTransformer::collection($pages),
            ];
            return response()->json($output);
        }
        return FullSliderItemTransformer::collection($this->sliderItemRepository->serverPagingFor($request));
    }

    public function store(Slider $slider, Request $request)
    {
        $data = $request->all();

        $data['slider_id'] = $slider->id;
        $rules = [
            'medias_single.image'=> 'required|numeric',
            'title' => 'required'
        ];
        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames([
            'medias_single.image' => __('Image'),
            'title' => __('Title'),
            'status' => __('Status'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        if (\Arr::get($data,'id') === null) {
            $slider = $this->sliderItemRepository->create($data);
        } else {
            $slider = $this->sliderItemRepository->find($data['id']);
            $this->sliderItemRepository->update($slider, $data);
        }

        return new SliderTransformer($slider);
    }

    public function delete(Slider $slider, SliderItem $slide)
    {
        $ok = $this->sliderItemRepository->destroy($slide);
        return response()->json(['error' => !$ok]);
    }
}
