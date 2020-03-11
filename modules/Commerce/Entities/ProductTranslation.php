<?php

namespace Modules\Commerce\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $table = "commerce__product_translations";
    protected $fillable = ['title', 'slug', 'body', 'excerpt'];
}
