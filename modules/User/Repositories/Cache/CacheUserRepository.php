<?php


namespace Modules\User\Repositories\Cache;


use Modules\User\Entities\User;
use Modules\User\Entities\UserToken;
use Modules\User\Repositories\UserRepository;

class CacheUserRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\User\Repositories\UserRepository
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function generateTokenFor(User $user)
    {
        return $this->remember(function () use ($user) {
            $this->repository->generateTokenFor($user);
        });
    }
}
