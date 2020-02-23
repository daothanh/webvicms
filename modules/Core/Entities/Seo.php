<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seos';
    protected $fillable = ['entity_id', 'entity_type', 'title', 'description', 'keywords', 'locale'];

    public function entityable()
    {
        return $this->morphTo();
    }
}
