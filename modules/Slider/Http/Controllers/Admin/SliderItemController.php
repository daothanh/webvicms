<?php

namespace Modules\Slider\Http\Controllers\Admin;

use Modules\Slider\Entities\SliderItem;
use Modules\Slider\Entities\Slider;
use Modules\Slider\Repositories\SliderItemRepository;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseAdminController;

class SliderItemController extends BaseAdminController
{
    protected $sliderItemRepository;

    public function __construct(SliderItemRepository $sliderItemRepository)
    {
        parent::__construct();
        $this->sliderItemRepository = $sliderItemRepository;
    }

    public function index(Slider $slider)
    {
        $this->seo()->setTitle(trans('slider::slide.title.Slides'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Sliders'), route('admin.slider.index'));
        $this->breadcrumb->addItem($slider->title);
        return $this->view('slider::admin.slider.slide.index', compact('slider'));
    }

    public function create(Slider $slider)
    {
        $this->seo()->setTitle(trans('slider::slide.title.Create a slide'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Sliders'), route('admin.slider.index'));
        $this->breadcrumb->addItem($slider->title, route('admin.slider.item.index', ['slider' => $slider->id]));
        $this->breadcrumb->addItem(trans('slider::slide.title.Create a slide'));
        return $this->view('slider::admin.slider.slide.create', compact('slider'));
    }

    public function edit(Slider $slider, SliderItem $slide)
    {
        if (!$slide) {
            abort(404);
        }
        $this->seo()->setTitle(trans('slider::slide.title.Edit a slide'));
        $this->breadcrumb->addItem(trans('slider::slider.title.Sliders'), route('admin.slider.index'));
        $this->breadcrumb->addItem($slider->title, route('admin.slider.item.index', ['slider' => $slider->id]));
        $this->breadcrumb->addItem(trans('slider::slide.title.Edit a slide'));
        return $this->view('slider::admin.slider.slide.edit', compact('slide', 'slider'));
    }

    public function store(Slider $slider, Request $request)
    {
        $lang = locale();
        $data = $request->all();
        $data['slider_id'] = $slider->id;
        $rules = [
            'medias_single.image'=> 'required|numeric',
        ];
        $translatedRules = [
            'title' => 'required|string',
        ];

        $translatedAttributeNames = [
            'title' => 'slider::slide.labels.Title',
            'description' => 'slider::slide.labels.Description',
            'slug' => 'Slug',
            'status' => 'slider::slide.labels.Status',
        ];
        $attributeNames = [
            'medias_single.image' => __('Image'),
            'status' => __('Status'),
        ];
        foreach ($translatedRules as $ruleKey => $rule) {
            $rules["{$lang}.{$ruleKey}"] = $rule;
        }

        foreach ($translatedAttributeNames as $attributeKey => $attributeName) {
            $attributeNames["{$lang}.{$attributeKey}"] = trans($attributeName, [], $lang);
        }

        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            if (\Arr::get($data,'id') !== null) {
                return redirect()->route('admin.slider.item.edit', ['slide' => \Arr::get($data,'id'), 'slider' => $slider->id])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->route('admin.slider.item.create', ['slider' => $slider->id])->withInput()->withErrors($validator->messages());
        }

        if (\Arr::get($data,'id') === null) {
            $this->sliderItemRepository->create($data);
        } else {
            $slide = $this->sliderItemRepository->find($data['id']);
            $this->sliderItemRepository->update($slide, $data);
            return redirect()->route('admin.slider.item.index', ['slider' => $slider->id])->withSuccess(__('slider::slide.messages.Slide was updated!'));
        }

        return redirect()->route('admin.slider.item.index', ['slider' => $slider->id])->withSuccess(__('slider::slide.messages.Slide was created!'));
    }
}
