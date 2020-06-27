<?php


namespace Modules\User\Repositories\Cache;


use Modules\User\Repositories\RoleRepository;

class CacheRoleRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\User\Repositories\RoleRepository
{

    protected $repository;

    public function __construct(RoleRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function findRoleByName($name)
    {
        return $this->remember(function () use ($name) {
            return $this->repository->findRoleByName($name);
        });
    }
}
