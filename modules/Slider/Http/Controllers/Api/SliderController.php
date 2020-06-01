<?php
namespace Modules\Slider\Http\Controllers\Api;

use Modules\Slider\Transformers\SliderTransformer;
use Modules\Slider\Entities\Slider;
use Modules\Slider\Transformers\FullSliderTransformer;
use Modules\Slider\Repositories\SliderRepository;
use Modules\Core\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class SliderController extends ApiController
{
    protected $sliderRepository;

    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $pages  = $this->sliderRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw"            => $request->get('draw'),
                "recordsTotal"    => $pages->total(),
                "recordsFiltered" => $pages->total(),
                'data'            => FullSliderTransformer::collection($pages),
            ];
            return response()->json($output);
        }
        return FullSliderTransformer::collection($this->sliderRepository->serverPagingFor($request));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'title'=> 'required|string',
        ];
        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames([
            'title' => __('Title'),
            'status' => __('Status'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        if (array_get($data,'id') === null) {
            $slider = $this->sliderRepository->create($data);
        } else {
            $slider = $this->sliderRepository->find($data['id']);
            $this->sliderRepository->update($slider, $data);
        }

        return new SliderTransformer($slider);
    }

    public function delete(Slider $page)
    {
        $ok = $this->sliderRepository->destroy($page);
        return response()->json(['error' => !$ok]);
    }
}
