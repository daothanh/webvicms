<?php
if (!function_exists('blog_post_categories')) {
    function blog_post_categories($pid = 0, $status = 1, $maxDepth = null)
    {
        return app(\Modules\Blog\Repositories\CategoryRepository::class)->getTree($pid, $status, $maxDepth);
    }
}

if (!function_exists('blog_posts')) {
    function blog_posts($params = [])
    {
        $request = \Request::merge(array_merge($params, ['status' => 1, 'locale' => locale()]));
        return app(\Modules\Blog\Repositories\PostRepository::class)->serverPagingFor($request, ['translations']);
    }
}

if (!function_exists('blog_latest_post')) {
    function blog_latest_post($params = [])
    {
        $query = app(\Modules\Blog\Repositories\PostRepository::class)->newQueryBuilder()
            ->where('status', '=', 1)
            ->whereHas('translations', function ($q) {
                $q->where('locale', '=', locale());
            });
        if (isset($params['is_trashed']) && $params['is_trashed'] !== null) {
            $query->onlyTrashed();
        }

        if (isset($params['title']) && $params['title'] !== null) {
            $name = $params['title'];
            $query->whereHas('translations', function ($query) use ($name) {
                $query->where('title', 'LIKE', "%{$name}%");
            });
        }

        if (isset($params['locale']) && $params['locale'] !== null) {
            $locale = $params['locale'];
            $query->whereHas('translations', function ($query) use ($locale) {
                $query->where('locale', '=', $locale);
            });
        }

        if (isset($params['status']) && $params['status'] !== null) {
            $status = $params['status'];
            $query->where('status', '=', $status);
        }

        if (isset($params['category_id']) && $params['category_id'] !== null) {
            $categoryId = $params['category_id'];
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category_id', '=', $categoryId);
            });
        }

        if (isset($params['category_ids']) && $params['category_ids'] !== null) {
            $categoryIds = $params['category_ids'];
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            });
        }
        return $query->orderBy('created_at', 'desc')
            ->first();
    }
}

if (!function_exists('blog_post_image')) {
    /**
     * @param \Modules\Blog\Entities\Post $post
     * @param null $thumbnail
     * @return string
     */
    function blog_post_image_url($post, $thumbnail = null)
    {
        if ($post->image) {
            return $post->image->getUrl($thumbnail);
        }
        return asset('uploads/media/no-image.jpg');
    }
}

if (!function_exists('blog_post_image')) {
    /**
     * @param \Modules\Blog\Entities\Post $post
     * @param null $thumbnail
     * @param array $attributes
     * @return string
     */
    function blog_post_image($post, $attributes = [], $thumbnail = null)
    {
        if ($post->image) {
            return $post->image->getImage($thumbnail, $attributes);
        }
        $htmlAttributes = [];
        foreach ($attributes as $attribute => $value) {
            if ($value) {
                $htmlAttributes[$attribute] = $attribute.'="'.$value.'"';
            }
        }
        return '<img src="'.asset('uploads/media/no-image.jpg').'" '.implode(" ", $htmlAttributes).'/>';
    }
}
