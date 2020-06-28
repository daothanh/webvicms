<?php


namespace Modules\Commerce\Repositories\Eloquent;


use Modules\Commerce\Events\CartWasCreated;

class EloquentCartRepository extends \Modules\Core\Repositories\Eloquent\EloquentBaseRepository implements \Modules\Commerce\Repositories\CartRepository
{
    public function create($data)
    {
        $cart = parent::create($data);
        event(new CartWasCreated($cart, $data));
    }
}
