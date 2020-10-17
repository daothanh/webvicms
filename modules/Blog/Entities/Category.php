<?php

namespace Modules\Blog\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Traits\MediaRelation;

/**
 * Modules\Blog\Entities\Category
 *
 * @property int $id
 * @property int|null $pid
 * @property int|null $order
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Media\Entities\Media[] $files
 * @property-read int|null $files_count
 * @property-read mixed $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Blog\Entities\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Core\Entities\Seo[] $seos
 * @property-read int|null $seos_count
 * @property-read \Modules\Blog\Entities\CategoryTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Blog\Entities\CategoryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Query\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Category withoutTrashed()
 * @mixin \Eloquent
 */
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
        return route('admin.blog.category.edit', ['id' => $this->id]);
    }

    public function getDuplicateUrl()
    {
        return route('admin.blog.category.duplicate', ['id' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.blog.category.delete', ['id' => $this->id]);
    }

    public function getForceDeleteUrl()
    {
        return route('api.blog.category.force-delete', ['id' => $this->id]);
    }

    public function getRestoreUrl()
    {
        return route('api.blog.category.restore', ['id' => $this->id]);
    }
}
