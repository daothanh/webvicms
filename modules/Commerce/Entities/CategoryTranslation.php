<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $table = 'commerce__category_translations';
    protected $fillable = ['name', 'slug', 'body'];
}
