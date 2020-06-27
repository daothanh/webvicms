<?php


namespace Modules\Slider\Repositories\Cache;


use Modules\Slider\Repositories\SliderRepository;

class CacheSliderRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\Slider\Repositories\SliderRepository
{
    protected $repository;

    public function __construct(SliderRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }
}
