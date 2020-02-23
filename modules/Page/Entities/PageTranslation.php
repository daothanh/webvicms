<?php

namespace Modules\Page\Entities;

use Modules\Page\Events\PageContentIsRendering;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'page_translations';
    protected $fillable = [
        'page_id',
        'title',
        'slug',
        'body',
    ];

    public function getBodyAttribute($body)
    {
        event($event = new PageContentIsRendering($body));

        return $event->getBody();
    }
}
