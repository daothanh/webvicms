<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['category', 'key', 'value'];
}
