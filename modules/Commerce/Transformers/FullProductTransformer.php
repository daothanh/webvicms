<?php

namespace Modules\Commerce\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Media\Transformers\MediaTransformer;

class FullProductTransformer extends Resource
{
    public function toArray($request)
    {
        $productSeo = $this->seoByLocale(locale());

        $productData = [
            'id' => $this->id,
            'status' => $this->status,
            'image' => $this->image ? new MediaTransformer($this->image) : null,
            'created_at' => $this->created_at->format('d/m/Y'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y') : null,
            'seo' => $productSeo ? new SeoTransformer($productSeo) : null,
            'urls' => [
                'public' => $this->getUrl(),
                'edit' => $this->getEditUrl(),
                'duplicate' => $this->getDuplicateUrl(),
                'delete' => $this->getDeleteUrl(),
                'forcedelete' => $this->getForceDeleteUrl(),
                'restore' => $this->getRestoreUrl()
            ],
        ];
        foreach (locales() as $locale) {
            $productData[$locale] = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $productData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
            }
        }

        return $productData;
    }
}
