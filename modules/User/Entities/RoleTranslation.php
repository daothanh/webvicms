<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'role_id'];
    protected $table = 'role_translations';
}
