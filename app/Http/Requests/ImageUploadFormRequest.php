<?php

namespace App\Http\Requests;

use App\Rules\ExtensionWithoutMimeCheck;
use Illuminate\Foundation\Http\FormRequest;

class ImageUploadFormRequest extends FormRequest
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
        // maximum number of files allowed to upload in a batch
        $uploadLimit = config('app.fileUpload.limit');

        // allowed file extensions for uploaded files
        $allowedExtensions = config('app.fileUpload.extensions');

        return [
            'files' => 'required|array|min:1|max:'.$uploadLimit,
            'files.*' => ['file', new ExtensionWithoutMimeCheck($allowedExtensions)],
            'batch' => 'required|numeric|exists:csv_data,import_batch'
        ];
    }
}
