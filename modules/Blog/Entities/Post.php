<?php

namespace Modules\Blog\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Traits\MediaRelation;

class Post extends Model
{
    use MediaRelation, Translatable, Seoable, SoftDeletes;
    protected $table = "blog__posts";
    protected $fillable = ['status'];
    public $translatedAttributes = ['title', 'slug', 'body', 'excerpt'];

    public function getImageAttribute()
    {
        return $this->filesByZone('image')->first();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog__category_post', 'post_id', 'category_id');
    }

    public function getUrl()
    {
        return route('blog.post.detail', ['slug' => $this->slug]);
    }

    public function getEditUrl()
    {
        return route('admin.blog.post.edit', ['id' => $this->id]);
    }

    public function getDuplicateUrl()
    {
        return route('admin.blog.post.duplicate', ['id' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.blog.post.delete', ['id' => $this->id]);
    }

    public function getForceDeleteUrl()
    {
        return route('api.blog.post.force-delete', ['id' => $this->id]);
    }

    public function getRestoreUrl()
    {
        return route('api.blog.post.restore', ['id' => $this->id]);
    }
}
