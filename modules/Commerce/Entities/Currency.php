<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'commerce__currencies';
    protected $fillable = ['name', 'code', 'symbol', 'position'];
}
