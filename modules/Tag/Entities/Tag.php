<?php

namespace Modules\Tag\Entities;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['namespace', 'slug', 'name'];

    public function getEditUrl(){
        return route('admin.tag.edit', ['id' => $this->id]);
    }

    public function getDeleteUrl(){
        return route('api.tag.delete', ['id' => $this->id]);
    }

    public function getUrl()
    {
        return route('tag', ['slug' => $this->slug]);
    }
}
