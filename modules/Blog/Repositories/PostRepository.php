<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Entities\Post;
use Modules\Core\Repositories\BaseRepository;

interface PostRepository extends BaseRepository {
    /**
     * @param $slug
     * @return Post|null
     */
    public function findBySlug($slug);
    public function forceDestroy($post);
}
