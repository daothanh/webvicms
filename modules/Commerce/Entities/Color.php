<?php

namespace Modules\Commerce\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use Translatable;

    protected $table = 'commerce__colors';
    protected $fillable = ['code', 'position'];
    public $translatedAttributes = ['name', 'description'];
}
