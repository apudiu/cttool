<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit_log extends Model
{
    protected $fillable = [
        'time', 'user', 'ip', 'action', 'details'
    ];
}
