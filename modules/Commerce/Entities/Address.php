<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'commerce__addresses';
    protected $fillable = ['name', 'email', 'phone', 'address', 'additional_address', 'company', 'province_id', 'district_id'];
}
