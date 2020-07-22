<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class PostTransformer extends Resource
{
    public function toArray($request)
    {
        $locale = \App::getLocale();
        $translatedPost = optional($this->translate($locale));
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'status' => $this->status,
            'translations' => [
                'title' => $translatedPost->title,
                'slug' => $translatedPost->slug,
                'excerpt' => $translatedPost->excerpt,
                'body' => $translatedPost->body,
            ],
            'urls' => [
                'delete_url' => route('api.blog.post.destroy', $this->id),
            ],
        ];
    }
}
