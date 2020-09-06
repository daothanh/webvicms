<?php

namespace Modules\Testimonial\Transformers;

use Modules\Media\Transformers\MediaTransformer;
use Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Resources\Json\JsonResource;

class FullApiTestimonialTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $post =  [
            'id' => $this->id,
            'photo' => $this->photo ? new MediaTransformer($this->photo) : null,
            'status' => $this->status,
            'name' => $this->name,
            'position' => $this->position,
            'content' => \Str::limit($this->content, 100),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'urls' => [
                'edit' => $this->getEditUrl(),
                'delete' => $this->getDeleteUrl(),
                'forcedelete' => $this->getForceDeleteUrl(),
                'restore' => $this->getRestoreUrl()
            ]
        ];
        $locales = \Settings::get('website', 'locales', [config('translatable.locale')]);
        foreach ($locales as $locale) {
            $post[$locale] = [];
            $translatedTerm = $this->translateOrNew($locale);
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $post[$locale][$translatedAttribute] = $translatedTerm->$translatedAttribute;
            }
        }
        return $post;
    }
}
