<?php
namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class Website extends Model {
    use MediaRelation;
    public $id = 1;
    public function getLogoAttribute() {
        return $this->filesByZone('logo')->first();
    }
    public function getFaviconAttribute() {
        return $this->filesByZone('favicon')->first();
    }
}
