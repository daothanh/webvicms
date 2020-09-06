<?php

namespace Modules\Commerce\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Traits\MediaRelation;
use Modules\Tag\Traits\TaggableTrait;

class Product extends Model
{
    use MediaRelation, Translatable, Seoable, SoftDeletes, TaggableTrait;
    protected $table = "commerce__products";
    protected $fillable = ['price', 'sale_price', 'currency', 'status'];
    public $translatedAttributes = ['title', 'slug', 'body', 'excerpt'];

    public function getImageAttribute()
    {
        return $this->filesByZone('image')->first();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'commerce__category_product', 'product_id', 'category_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'commerce__product_color', 'product_id', 'color_id');
    }

    public function getUrl()
    {
        return route('commerce.product.detail', ['slug' => $this->slug]);
    }

    public function getEditUrl()
    {
        return route('admin.commerce.product.edit', ['id' => $this->id]);
    }

    public function getDuplicateUrl()
    {
        return route('admin.commerce.product.duplicate', ['id' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.commerce.product.delete', ['id' => $this->id]);
    }

    public function getForceDeleteUrl()
    {
        return route('api.commerce.product.force-delete', ['productId' => $this->id]);
    }

    public function getRestoreUrl()
    {
        return route('api.commerce.product.restore', ['id' => $this->id]);
    }
}
