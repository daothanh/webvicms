<?php

namespace Modules\Commerce\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface ProductRepository extends BaseRepository {
    public function findBySlug($slug);
    public function forceDestroy($page);
}
