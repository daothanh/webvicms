<?php
namespace Modules\Page\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Page\Repositories\PageRepository;

class CachePageRepository extends BaseCacheDecorator implements PageRepository {
    protected $repository;
    public function __construct(PageRepository $pageRepository)
    {
        parent::__construct();
        $this->repository = $pageRepository;
    }

    public function forceDestroy($page)
    {
        return $this->repository->forceDestroy($page);
    }
}
