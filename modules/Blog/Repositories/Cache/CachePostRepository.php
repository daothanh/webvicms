<?php


namespace Modules\Blog\Repositories\Cache;


use Illuminate\Http\Request;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePostRepository extends BaseCacheDecorator implements PostRepository
{
    protected $repository;
    public function __construct(PostRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function forceDestroy($post)
    {
        return $this->remember(function () use ($post) {
            return $this->repository->forceDestroy($post);
        });
    }
}
