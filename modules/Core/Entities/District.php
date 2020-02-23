<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $fillable = ['province_id', 'name', 'status'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}
