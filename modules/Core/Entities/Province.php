<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $fillable = ['name', 'order', 'translate'];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
}
