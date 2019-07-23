<?php

namespace App\Http\Requests;

use App\Rules\ExtensionWithoutMimeCheck;
use Illuminate\Foundation\Http\FormRequest;

class CSVFileFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'csv_file' => [
                 'required','file',

                 // this will ensure only files with mimes for CSV & plain text will be uploaded
                 'mimes:csv,txt',
                 'min:1','max:7168',

                 // as user also can upload text file, here we restricting only to csv extension
                 // regardless mimes
                 new ExtensionWithoutMimeCheck(['csv'])
             ]
        ];
    }

    /**
     * Custom attributes
     * @return array
     */
    public function attributes()
    {
        return [
            'csv_file' => 'CSV File'
        ];
    }
}
