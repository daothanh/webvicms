<?php

namespace Modules\User\Entities;

use Astrotomic\Translatable\Translatable;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use Translatable;

    protected $table = 'roles';
    protected $fillable = ['name', 'guard_name'];
    public $translatedAttributes = ['title'];

    public function getEditUrl(){
        return route('admin.role.edit', ['id' => $this->id]);
    }

    public function getDeleteUrl(){
        return route('api.role.delete', ['id' => $this->id]);
    }
}
