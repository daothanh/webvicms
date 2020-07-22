<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\JsonResource as Resource;
use Modules\Media\Transformers\MediaTransformer;

class FullPageTransformer extends Resource
{
    public function toArray($request)
    {
        $pageSeo = $this->seoByLocale(locale());

        $pageData = [
            'id' => $this->id,
            'is_can_delete' => $this->is_can_delete,
            'is_home' => $this->is_home,
            'layout' => $this->layout,
            'status' => $this->status,
            'featured_image' => $this->featured_image ? new MediaTransformer($this->featured_image) : null,
            'created_at' => $this->created_at->format('d/m/Y'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d/m/Y') : null,
            'seo' => $pageSeo ? new SeoTransformer($pageSeo) : null,
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
            $pageData[$locale] = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $pageData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
            }
        }

        return $pageData;
    }
}
