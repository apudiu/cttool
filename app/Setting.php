<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'php_name', 'docudex_path', 'files_path', 'config_path'
    ];

    /**
     * Relations
     */
}
