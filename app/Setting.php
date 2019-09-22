<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'php_name', 'docudex_path', 'files_path', 'config_path'
    ];

    /**
     * Relations
     */
}
