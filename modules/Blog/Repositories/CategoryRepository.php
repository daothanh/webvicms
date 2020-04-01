<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Entities\Category;
use Modules\Core\Repositories\BaseRepository;

interface CategoryRepository extends BaseRepository {
    /**
     * @param $slug
     * @return Category|null
     */
    public function findBySlug($slug);
    public function forceDestroy($page);
    public function getTree($pid = 0, $status = null, $maxDepth = null);
    public function getCategories();
}
