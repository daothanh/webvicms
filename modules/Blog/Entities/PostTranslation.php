<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    protected $table = "blog__post_translations";
    protected $fillable = ['title', 'slug', 'body', 'excerpt'];
}
