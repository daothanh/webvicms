<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FullSliderTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'items' => $this->items()->count() ? SliderItemTransformer::collection($this->items) : null,
            'urls' => [
                'edit' => $this->getEditUrl(),
                'delete' => $this->getDeleteUrl(),
                'slides' => $this->getSlidesUrl()
            ]
        ];
    }
}
