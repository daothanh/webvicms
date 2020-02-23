<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class ErrorReport extends Model
{
    protected $table = 'error_reports';
    protected $fillable = ['key', 'line', 'message', 'file', 'data', 'trace'];
}
