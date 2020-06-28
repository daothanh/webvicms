<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class CartBuyer extends Model
{
    public $table = 'commerce__cart_buyers';
    protected $fillable = ['name', 'phone', 'user_id', 'cart_id', 'email'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }
}
