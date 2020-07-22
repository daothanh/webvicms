<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\JsonResource as Resource;
use Modules\Media\Transformers\MediaTransformer;

class FullPostTransformer extends Resource
{
    public function toArray($request)
    {
        $postSeo = $this->seoByLocale(locale());

        $postData = [
            'id' => $this->id,
            'status' => $this->status,
            'image' => $this->image ? new MediaTransformer($this->image) : null,
            'created_at' => $this->created_at->format('d/m/Y'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y') : null,
            'seo' => $postSeo ? new SeoTransformer($postSeo) : null,
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
            $postData[$locale] = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $postData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
            }
        }

        return $postData;
    }
}
