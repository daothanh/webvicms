<?php

namespace Modules\Page\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\Seoable;
use Modules\Media\Entities\Media;
use Modules\Media\Traits\MediaRelation;

/**
 * Modules\Page\Entities\Page
 *
 * @property int $id
 * @property string|null $layout
 * @property bool|null $is_can_delete
 * @property bool|null $is_home
 * @property bool|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Page\Entities\CustomField[] $customFields
 * @property-read int|null $custom_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Media\Entities\Media[] $files
 * @property-read int|null $files_count
 * @property-read mixed $featured_image
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Core\Entities\Seo[] $seos
 * @property-read int|null $seos_count
 * @property-read \Modules\Page\Entities\PageTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Page\Entities\PageTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Page\Entities\Page onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereIsCanDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereIsHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page withTranslation()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Page\Entities\Page withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Page\Entities\Page withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $define_fields
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\Page whereDefineFields($value)
 */
class Page extends Model
{
    use Translatable, MediaRelation, SoftDeletes, Seoable;

    protected $table = 'page__pages';
    protected $fillable = ['layout', 'status', 'is_can_delete', 'is_home', 'define_fields'];
    public $translatedAttributes = ['title', 'slug', 'filename', 'description', 'locale', 'page_id', 'code_file'];

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

    public function customFields()
    {
        return $this->hasMany(CustomField::class, 'page_id', 'id')->where('locale', '=', locale());
    }

    public function customField($name)
    {
        if ($this->customFields) {
            foreach ($this->customFields as $pField)
            {
                if ($pField->name === $name) {
                    return $pField;
                }
            }
        }
        return null;
    }

    public function cfValue($name)
    {
        $cf = $this->customField($name);
        if ($cf) {
            if ($cf->type === 'image') {
                return Media::whereIn('id', explode(",",$cf->value))->first();
            } else {
                return $cf->value;
            }
        }
        return null;
    }

    public function cfImage($name) {
        $cf = $this->cfValue($name);
        if ($cf) {
            return $cf->path->getUrl();
        }
        return '';
    }

    public function decodeFields() {
        if ($this->define_fields) {
            return json_decode($this->define_fields);
        }
        return null;
    }
}
