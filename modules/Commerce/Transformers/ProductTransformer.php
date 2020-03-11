<?php

namespace Modules\Commerce\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ProductTransformer extends Resource
{
    public function toArray($request)
    {
        $locale = \App::getLocale();
        $translatedProduct = optional($this->translate($locale));
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'status' => $this->status,
            'translations' => [
                'title' => $translatedProduct->title,
                'slug' => $translatedProduct->slug,
                'excerpt' => $translatedProduct->excerpt,
                'body' => $translatedProduct->body,
            ],
            'urls' => [
                'delete_url' => route('api.commerce.product.destroy', $this->id),
            ],
        ];
    }
}
