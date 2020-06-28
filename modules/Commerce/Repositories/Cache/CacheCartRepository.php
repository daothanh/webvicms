<?php


namespace Modules\Commerce\Repositories\Cache;


use Modules\Commerce\Repositories\CartRepository;

class CacheCartRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\Commerce\Repositories\CartRepository
{
    protected $repository;
    public function __construct(CartRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }
}
