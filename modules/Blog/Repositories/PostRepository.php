<?php

namespace Modules\Blog\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface PostRepository extends BaseRepository {
    public function findBySlug($slug);
    public function forceDestroy($page);
}
