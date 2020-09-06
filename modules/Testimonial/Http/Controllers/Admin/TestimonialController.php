<?php

namespace Modules\Testimonial\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\BaseAdminController;
use Modules\Testimonial\Entities\Testimonial;
use Modules\Testimonial\Repositories\TestimonialRepository;
use Illuminate\Http\Request;

class TestimonialController extends BaseAdminController
{
    protected $testimonialRepository;

    public function __construct(TestimonialRepository $testimonialRepository)
    {
        parent::__construct();
        $this->testimonialRepository = $testimonialRepository;
    }

    public function index()
    {
        return $this->view('testimonial::admin.testimonial.index');
    }

    public function trash()
    {
        return $this->view('testimonial::admin.testimonial.trash');
    }

    public function create()
    {
        return $this->view('testimonial::admin.testimonial.create');
    }

    public function edit(Testimonial $testimonial)
    {
        if (!$testimonial) {
            abort(404);
        }
        $testimonial->load('translations');
        return $this->view('testimonial::admin.testimonial.edit', compact('testimonial'));
    }

    public function store(Request $request)
    {
        $locales = locales();
        $data = $request->all();

        $rules = [
            'status'=> 'required',
        ];
        $translatedRules = [
            'name'=> 'required|string',
            'content'=> 'required',
        ];

        $attributeNames = [
            'status' => __('Status'),
        ];
        $translatedAttributeNames = [
            'name' => __('testimonial::testimonial.labels.Name'),
            'content' => __('testimonial::testimonial.labels.Content'),
            'position' => __('testimonial::testimonial.labels.Position'),
        ];

        foreach ($locales as $locale) {
            foreach ($translatedRules as $ruleKey => $rule) {
                $rules["{$locale}.{$ruleKey}"] = $rule;
            }

            foreach ($translatedAttributeNames as $attributeKey => $attributeName) {
                $attributeNames["{$locale}.{$attributeKey}"] = $attributeName;
            }
        }

        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            if (\Arr::get($data,'id') !== null) {
                return redirect()->route('admin.testimonial.edit', ['testimonial' => \Arr::get($data,'id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->route('admin.testimonial.create')->withInput()->withErrors($validator->messages());
        }

        if (\Arr::get($data,'id') === null) {
            $this->testimonialRepository->create($data);
        } else {
            $testimonial = $this->testimonialRepository->find($data['id']);
            $this->testimonialRepository->update($testimonial, $data);
            return redirect()->route('admin.testimonial.index')->withSuccess(__('testimonial::testimonial.messages.Testimonial was updated!'));
        }

        return redirect()->route('admin.testimonial.index')->withSuccess(__('testimonial::testimonial.messages.Testimonial was created!'));
    }
}
