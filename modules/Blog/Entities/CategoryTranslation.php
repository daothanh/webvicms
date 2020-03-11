<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $table = 'blog__category_translations';
    protected $fillable = ['name', 'slug', 'body'];
}
