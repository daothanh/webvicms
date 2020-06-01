<?php

namespace Modules\Slider\Entities;

use Astrotomic\Translatable\Translatable;
use Modules\Media\Traits\MediaRelation;
use Illuminate\Database\Eloquent\Model;

class SliderItemTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'slider__item_translations';
    public $fillable = ['title', 'description'];


}
