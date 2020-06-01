<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Media\Transformers\MediaTransformer;

class SliderItemTransformer extends JsonResource
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
            'image' => $this->image ? new MediaTransformer($this->image) : null,
            'title' => $this->title,
            'description' => $this->excerpt,
            'status' => $this->status,
            'url' => $this->url,
            'url_target' => $this->url_target,
            'order' => $this->order,
            'created_at' => $this->created_at
        ];
    }
}
