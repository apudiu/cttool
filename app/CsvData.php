<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    protected $fillable = [
        'user_id','import_batch','document_path','client_id','ca_or_file_sl',
        'document_type','customer','father','mother','spouse','company','dob',
        'passport','mobile','tin','email','nid','shelf','rack','card_or_product',
        'card_fs','card_holder','card_type','product_type','pwpd','le_date','serial_le',
        'customer_unicode','fathers_unicode','mothers_unicode','nid_dob','nid_no',
        'tax_payer','tax_payer_tin','issue_date','interviewer','applicant','profession',
        'education','sel_or_kyc_date','loi','loi_date','account_no','account_type',
        'ac_status','print_date','bank','branch','digitized_by','file_type','batch','file_name',
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
     * Model will be having many files (matching import batch)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files() {
        return $this->belongsToMany(File::class);
    }


    /**
     * Custom attributes
     */
}
