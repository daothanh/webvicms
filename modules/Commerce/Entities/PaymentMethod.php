<?php

namespace Modules\Commerce\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use Translatable;

    protected $table = 'commerce__payment_methods';
    protected $fillable = ['handler', 'position', 'active'];
}
