<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\JsonResource as Resource;
use Modules\Media\Transformers\MediaTransformer;

class FullCategoryTransformer extends Resource
{
    public function toArray($request)
    {
        $categorySeo = $this->seoByLocale(locale());

        $categoryData = [
            'id' => $this->id,
            'status' => $this->status,
            'image' => $this->image ? new MediaTransformer($this->image) : null,
            'created_at' => $this->created_at->format('d/m/Y'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y') : null,
            'urls' => [
                'public' => $this->getUrl(),
                'edit' => $this->getEditUrl(),
                'delete' => $this->getDeleteUrl(),
                'forcedelete' => $this->getForceDeleteUrl(),
                'restore' => $this->getRestoreUrl()
            ],
        ];
        foreach (locales() as $locale) {
            $categoryData[$locale] = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $categoryData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
            }
        }

        return $categoryData;
    }
}
