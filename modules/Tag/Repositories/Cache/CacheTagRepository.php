<?php


namespace Modules\Tag\Repositories\Cache;


use Modules\Tag\Repositories\TagRepository;

class CacheTagRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\Tag\Repositories\TagRepository
{

    protected $repository;

    public function __construct(TagRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function allForNamespace($namespace)
    {
        return $this->remember(function () use ($namespace) {
            return $this->repository->allForNamespace($namespace);
        });
    }
}
