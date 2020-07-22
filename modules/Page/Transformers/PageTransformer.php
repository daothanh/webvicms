<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class PageTransformer extends Resource
{
    public function toArray($request)
    {
        $locale = \App::getLocale();
        $translatedPage = optional($this->translate($locale));
        return [
            'id' => $this->id,
            'is_can_delete' => $this->is_can_delete,
            'is_home' => $this->is_home,
            'layout' => $this->layout,
            'created_at' => $this->created_at->format('d-m-Y'),
            'translations' => [
                'title' => $translatedPage->title,
                'slug' => $translatedPage->slug,
                'status' => $translatedPage->status,
            ],
            'urls' => [
                'delete_url' => route('api.page.destroy', $this->id),
            ],
        ];
    }
}
