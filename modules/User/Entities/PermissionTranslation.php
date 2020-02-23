<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class PermissionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'permission_id'];
    protected $table = 'permission_translations';
}
