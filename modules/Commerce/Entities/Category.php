<?php

namespace Modules\Commerce\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Traits\MediaRelation;

class Category extends Model
{
    use Translatable, MediaRelation, SoftDeletes, Seoable;
    protected $table = 'commerce__categories';
    protected $fillable = ['pid', 'status', 'order'];
    public $translatedAttributes = ['name', 'slug', 'body'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'commerce__category_product', 'category_id', 'product_id');
    }

    public function getImageAttribute()
    {
        return $this->filesByZone('image')->first();
    }

    public function getUrl()
    {
        return route('commerce.category', ['slug' => $this->slug]);
    }

    public function getEditUrl()
    {
        return route('admin.commerce.category.edit', ['category1' => $this->id]);
    }

    public function getDuplicateUrl()
    {
        return route('admin.commerce.category.duplicate', ['category' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.commerce.category.delete', ['category' => $this->id]);
    }

    public function getForceDeleteUrl()
    {
        return route('api.commerce.category.force-delete', ['categoryId' => $this->id]);
    }

    public function getRestoreUrl()
    {
        return route('api.commerce.category.restore', ['categoryId' => $this->id]);
    }
}
