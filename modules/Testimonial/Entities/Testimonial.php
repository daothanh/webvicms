<?php

namespace Modules\Testimonial\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Media\Traits\MediaRelation;

class Testimonial extends Model
{
    use Translatable, MediaRelation, SoftDeletes;

    protected $fillable = ['status'];
    public $translatedAttributes = ['name', 'position', 'content'];

    public function getPhotoAttribute()
    {
        return $this->filesByZone('photo')->first();
    }

    public function getEditUrl()
    {
        return route('admin.testimonial.edit', ['testimonial' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.testimonial.delete', ['testimonial' => $this->id]);
    }

    public function getForceDeleteUrl()
    {
        return route('api.testimonial.force-delete', ['testimonialId' => $this->id]);
    }

    public function getRestoreUrl()
    {
        return route('api.testimonial.restore', ['testimonialId' => $this->id]);
    }

    public function getUrl()
    {
        return route('testimonial.detail', ['slug' => $this->slug]);
    }
}
