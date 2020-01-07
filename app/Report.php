<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['import_batch', 'name', 'status', 'reason', 'time'];
}
