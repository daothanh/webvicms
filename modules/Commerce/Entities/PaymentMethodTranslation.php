<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodTranslation extends Model
{
    protected $table = 'commerce__payment_method_translations';
    protected $fillable = ['name'];
    public $timestamps = false;
}
