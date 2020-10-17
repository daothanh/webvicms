<?php

namespace Modules\Blog\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Entities\Media;
use Modules\Media\Traits\MediaRelation;

/**
 * Modules\Blog\Entities\Post
 *
 * @property int $id
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Blog\Entities\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Media[] $files
 * @property-read int|null $files_count
 * @property-read \Modules\Media\Entities\Media|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Core\Entities\Seo[] $seos
 * @property-read int|null $seos_count
 * @property-read \Modules\Blog\Entities\PostTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Blog\Entities\PostTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Query\Builder|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Post translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Post withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Post withoutTrashed()
 * @mixin \Eloquent
 */
class Post extends Model
{
    use MediaRelation, Translatable, Seoable, SoftDeletes;
    protected $table = "blog__posts";
    protected $fillable = ['status'];
    public $translatedAttributes = ['title', 'slug', 'body', 'excerpt'];

    /**
     * @return Media|null
     */
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
