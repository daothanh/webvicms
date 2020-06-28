<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Cart extends Model
{
    public $table = 'commerce__carts';
    protected $fillable = ['user_id', 'subtotal', 'total', 'vat', 'items', 'status'];

    public function deliveryAddress()
    {
        return $this->hasOne(CartDeliveryAddress::class, 'cart_id', 'id');
    }

    public function buyer()
    {
        return $this->hasOne(CartBuyer::class, 'cart_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
