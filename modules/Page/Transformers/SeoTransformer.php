<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class SeoTransformer extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords
        ];
    }
}
