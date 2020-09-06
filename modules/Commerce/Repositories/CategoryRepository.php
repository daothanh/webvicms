<?php

namespace Modules\Commerce\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface CategoryRepository extends BaseRepository {
    public function findBySlug($slug);
    public function forceDestroy($page);
    public function getTree($pid = 0, $status = null, $maxDepth = null);
    public function getCategories($forSelect = false);
}
