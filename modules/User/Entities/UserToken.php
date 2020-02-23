<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'user_tokens';
    protected $fillable = ['user_id', 'access_token'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
