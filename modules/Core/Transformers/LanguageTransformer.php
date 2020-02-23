<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class LanguageTransformer extends Resource
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
