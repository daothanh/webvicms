<?php
namespace Modules\Core\Traits;

use Modules\Core\Entities\Seo;

trait Seoable {
    public function seos()
    {
        return $this->hasMany(Seo::class, 'entity_id', 'id')->where('entity_type', '=', get_class($this));
    }

    public function seoByLocale($locale)
    {
        return $this->seos->where('locale', '=', $locale)->first();
    }
}
