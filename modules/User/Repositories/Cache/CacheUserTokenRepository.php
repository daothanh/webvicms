<?php


namespace Modules\User\Repositories\Cache;


use Modules\User\Repositories\UserTokenRepository;

class CacheUserTokenRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\User\Repositories\UserTokenRepository
{
    protected $repository;

    public function __construct(UserTokenRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }
}
