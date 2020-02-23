<?php

namespace Modules\Tag\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TagTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'namespace' => $this->namespace,
            'urls' => [
                'edit' => $this->getEditUrl(),
                'delete' => $this->getDeleteUrl(),
//                'public' => $this->getUrl()
            ]
        ];
    }
}
