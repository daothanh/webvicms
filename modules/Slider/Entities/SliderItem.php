<?php

namespace Modules\Slider\Entities;

use Astrotomic\Translatable\Translatable;
use Modules\Media\Traits\MediaRelation;
use Illuminate\Database\Eloquent\Model;

class SliderItem extends Model
{
    use Translatable, MediaRelation;

    protected $table = 'slider__items';
    protected $fillable = ['slider_id', 'url', 'url_target', 'order', 'status'];
    public $translatedAttributes = ['title', 'description'];

    public function getImageAttribute() {
        return $this->filesByZone('image')->first();
    }

    public function slider()
    {
        return $this->belongsTo(Slider::class, 'slider_id', 'id');
    }

    public function getEditUrl()
    {
        return route('admin.slider.item.edit', ['slide' => $this->id, 'slider' => $this->slider_id]);
    }

    public function getDeleteUrl()
    {
        return route('api.slider.item.delete', ['slide' => $this->id, 'slider' => $this->slider_id]);
    }
}
