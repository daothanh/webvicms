<?php

namespace Modules\Page\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface PageRepository extends BaseRepository {
    public function findBySlug($slug);
    public function forceDestroy($page);
}
