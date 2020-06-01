<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Page\Entities\CustomField
 *
 * @property-read \Modules\Page\Entities\Page $page
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\CustomField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\CustomField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Page\Entities\CustomField query()
 * @mixin \Eloquent
 */
class CustomField extends Model
{
    public $table = 'page__custom_fields';
    protected $fillable = ['page_id', 'name', 'type', 'value', 'label', 'locale'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
}
