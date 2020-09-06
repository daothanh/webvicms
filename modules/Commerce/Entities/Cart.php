<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Cart extends Model
{
    public $table = 'commerce__carts';
    protected $fillable = ['user_id', 'content', 'currency_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
