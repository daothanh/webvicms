<?php


namespace Modules\Commerce\Repositories\Cache;


use Modules\Commerce\Repositories\ProductRepository;

class CacheProductRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\Commerce\Repositories\ProductRepository
{
    protected $repository;

    public function __construct(ProductRepository $repository)
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
}
