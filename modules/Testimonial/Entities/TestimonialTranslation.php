<?php

namespace Modules\Testimonial\Entities;

use Illuminate\Database\Eloquent\Model;

class TestimonialTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'position', 'content'];
}
