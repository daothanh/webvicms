<?php

namespace Modules\Page\Entities;

use Modules\Page\Events\PageContentIsRendering;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Page\Entities\PageTranslation
 *
 * @property int $id
 * @property int $page_id
 * @property string $locale
 * @property string $title
 * @property string $slug
 * @property string|null $filename
 * @property string|null $description
 * @property string|null $code_file
 * @property-read mixed $body
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereCodeFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereTitle($value)
 * @mixin \Eloquent
 * @property string|null $define_fields
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\PageTranslation whereDefineFields($value)
 */
class PageTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'page__page_translations';
    protected $fillable = [
        'page_id',
        'title',
        'slug',
        'filename',
        'description',
        'code_file'
    ];

    public function getBodyAttribute($body)
    {
        event($event = new PageContentIsRendering($body));

        return $event->getBody();
    }
}
