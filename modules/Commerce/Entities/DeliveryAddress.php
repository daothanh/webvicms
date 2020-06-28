<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    public $table = 'commerce__delivery_addresses';
    protected $fillable = ['name', 'phone', 'user_id', 'email', 'address', 'province_id', 'district_id'];
}
