<?php
if (!function_exists('tags')) {
    function tags(array $filters = [], $perPage = 10, $orderBy = 'created_at', $order = 'desc')
    {
        $tags = app(\Modules\Tag\Repositories\TagRepository::class)->newQueryBuilder();
        $attributes = ['namespace', 'slug', 'name'];
        if (!empty($filters)) {
            foreach ($filters as $attribute => $value) {
                if ($attribute === 0) {
                    $tags->whereRaw($value);
                } elseif (in_array($attribute, $attributes)) {
                    $tags->where($attribute, '=', $value);
                }
            }
        }
        $tags->orderBy($orderBy, $order);
        if ($perPage !== null) {
            return $tags->paginate($perPage);
        }
        return $tags->get();
    }
}
