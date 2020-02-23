<?php

namespace Modules\Page\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Traits\MediaRelation;

class Page extends Model
{
    use Translatable, MediaRelation, SoftDeletes, Seoable;

    protected $table = 'pages';
    protected $fillable = ['layout', 'status', 'is_can_delete', 'is_home'];
    public $translatedAttributes = ['title', 'slug', 'body', 'locale', 'page_id'];

    protected $casts = [
        'status' => 'boolean',
        'is_home' => 'boolean',
        'is_can_delete' => 'boolean',
    ];

    public function getFeaturedImageAttribute()
    {
        return $this->filesByZone('featured_image')->first();
    }

    public function getUrl()
    {
        if ($this->slug) {
            return route('page', ['uri' => $this->slug]);
        }
        return null;
    }

    public function getEditUrl()
    {
        return route('admin.page.edit', ['page' => $this->id]);
    }

    public function getDuplicateUrl()
    {
        return route('admin.page.duplicate', ['page' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.page.delete', ['page' => $this->id]);
    }

    public function getForceDeleteUrl()
    {
        return route('api.page.force-delete', ['pageId' => $this->id]);
    }

    public function getRestoreUrl()
    {
        return route('api.page.restore', ['pageId' => $this->id]);
    }
}
