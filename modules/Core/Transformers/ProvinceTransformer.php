<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class ProvinceTransformer extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
