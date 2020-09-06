<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class ColorTranslation extends Model
{
    protected $table = 'commerce__color_translations';
    protected $fillable = ['name', 'description'];
    public $timestamps = false;
}
