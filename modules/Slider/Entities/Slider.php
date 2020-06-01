<?php

namespace Modules\Slider\Entities;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider__sliders';
    protected $fillable = ['title', 'description', 'status'];

    public function items()
    {
        return $this->hasMany(SliderItem::class, 'slider_id', 'id');
    }

    public function getEditUrl()
    {
        return route('admin.slider.edit', ['slider' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.slider.delete', ['slider' => $this->id]);
    }

    public function getSlidesUrl()
    {
        return route('admin.slider.item.index', ['slider' => $this->id]);
    }
}
