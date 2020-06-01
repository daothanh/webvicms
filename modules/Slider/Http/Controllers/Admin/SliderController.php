<?php

namespace Modules\Slider\Http\Controllers\Admin;

use Modules\Slider\Entities\Slider;
use Modules\Slider\Repositories\SliderRepository;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseAdminController;

class SliderController extends BaseAdminController
{
    protected $sliderRepository;

    public function __construct(SliderRepository $sliderRepository)
    {
        parent::__construct();
        $this->sliderRepository = $sliderRepository;
    }

    public function index()
    {
        $this->seo()->setTitle(trans('slider::slider.title.Sliders'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Sliders'), route('admin.slider.index'));
        return $this->view('slider::admin.slider.index');
    }

    public function create()
    {
        $this->seo()->setTitle(trans('slider::slider.title.Create a slider'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Sliders'), route('admin.slider.index'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Create a slider'));
        return $this->view('slider::admin.slider.create');
    }

    public function edit(Slider $slider)
    {
        if (!$slider) {
            abort(404);
        }
        $this->seo()->setTitle(trans('slider::slider.title.Create a slider'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Sliders'), route('admin.slider.index'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Edit a slider'));
        return $this->view('slider::admin.slider.edit', compact('slider'));
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
            if (\Arr::get($data,'id') !== null) {
                return redirect()->route('admin.slider.edit', ['slider' => \Arr::get($data,'id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->route('admin.slider.create')->withInput()->withErrors($validator->messages());
        }

        if (\Arr::get($data,'id') === null) {
            $this->sliderRepository->create($data);
        } else {
            $slider = $this->sliderRepository->find($data['id']);
            $this->sliderRepository->update($slider, $data);
            return redirect()->route('admin.slider.index')->withSuccess(__('slider::slider.messages.Slider was updated!'));
        }

        return redirect()->route('admin.slider.index')->withSuccess(__('slider::slider.messages.Slider was created!'));
    }
}
