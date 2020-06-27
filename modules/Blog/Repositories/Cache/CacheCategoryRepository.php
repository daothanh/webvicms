<?php
namespace Modules\Blog\Repositories\Cache;

use Modules\Blog\Repositories\CategoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCategoryRepository extends BaseCacheDecorator implements CategoryRepository {
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function forceDestroy($page)
    {
        return $this->remember(function () use ($page) {
            return $this->repository->forceDestroy($page);
        });
    }

    public function getTree($pid = 0, $status = null, $maxDepth = null)
    {
        return $this->remember(function () use ($pid, $status, $maxDepth) {
            return $this->repository->getTree($pid, $status, $maxDepth);
        });
    }

    public function getCategories()
    {
        return $this->remember(function () {
            return $this->repository->getCategories();
        });
    }
}
