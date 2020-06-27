<?php


namespace Modules\Slider\Repositories\Cache;


use Modules\Slider\Repositories\SliderItemRepository;

class CacheSliderItemRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\Slider\Repositories\SliderItemRepository
{
    protected $repository;

    public function __construct(SliderItemRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }
}
