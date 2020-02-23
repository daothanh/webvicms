<?php

namespace Modules\User\Entities;

use Astrotomic\Translatable\Translatable;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use Translatable;
    public $translatedAttributes = ['title'];
    public $fillable = ['name', 'guard_name'];
}
