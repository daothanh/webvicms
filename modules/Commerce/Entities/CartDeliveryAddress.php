<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class CartDeliveryAddress extends Model
{
    public $table = 'commerce__cart_delivery_addresses';
    protected $fillable = ['cart_id', 'name', 'phone', 'email', 'address', 'province_id', 'district_id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }
}
