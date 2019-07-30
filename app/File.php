<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'user_id', 'import_batch', 'name', 'path'
    ];

    /**
     * Relations
     */

    /**
     * Record owner
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Model will be having many csv data record (matching with import batch)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function csv_data() {
        return $this->belongsTo(CsvData::class, 'file_name', 'name');
    }
}
