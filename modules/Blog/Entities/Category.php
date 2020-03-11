<?php

namespace Modules\Blog\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Traits\MediaRelation;

class Category extends Model
{
    use Translatable, MediaRelation, SoftDeletes, Seoable;
    protected $table = 'blog__categories';
    protected $fillable = ['pid', 'status', 'order'];
    public $translatedAttributes = ['name', 'slug', 'body'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blog__category_post', 'category_id', 'post_id');
    }

    public function getImageAttribute()
    {
        return $this->filesByZone('image')->first();
    }
    public function getUrl()
    {
        return route('blog.category', ['slug' => $this->slug]);
    }

    public function getEditUrl()
    {
        return route('admin.blog.category.edit', ['category' => $this->id]);
    }

    public function getDuplicateUrl()
    {
        return route('admin.blog.category.duplicate', ['category' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.blog.category.delete', ['category' => $this->id]);
    }

    public function getForceDeleteUrl()
    {
        return route('api.blog.category.force-delete', ['categoryId' => $this->id]);
    }

    public function getRestoreUrl()
    {
        return route('api.blog.category.restore', ['categoryId' => $this->id]);
    }
}
