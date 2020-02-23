<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class ConnectedAccount extends Model
{
    protected $fillable = ['provider', 'provider_id', 'nickname', 'name','email', 'avatar', 'raw'];
}
