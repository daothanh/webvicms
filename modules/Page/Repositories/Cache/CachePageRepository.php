<?php
namespace Modules\Page\Repositories\Cache;

use Modules\Core\Repositories\BaseRepository;
use Modules\Page\Repositories\PageRepository;

class CachePageRepository implements BaseRepository {
    protected $repository;
    public function __construct(PageRepository $pageRepository)
    {
        $this->repository = $pageRepository;
    }
}
