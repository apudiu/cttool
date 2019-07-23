<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExtensionWithoutMimeCheck implements Rule
{
    protected $allowedExtensions;

    /**
     * Create a new rule instance.
     *
     * @param array $allowed
     */
    public function __construct(array $allowed)
    {
        $this->allowedExtensions = $allowed;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // uploaded file's original extension
        $fileExtension = $value->getClientOriginalExtension();

        return in_array($fileExtension, $this->allowedExtensions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid file type for :attribute, allowed: '. implode(', ', $this->allowedExtensions);
    }
}
