<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PassportSizePhoto implements Rule
{
    protected $failureReason = '';

    public function passes($attribute, $value)
    {
        // Check if it's a valid image file
        // if (!$value->isValid() || !in_array($value->getClientOriginalExtension(), ['jpg', 'jpeg'])) {
        //     $this->failureReason = 'file type';
        //     return false;
        // }

        // Check file size (100 KB = 100 * 1024 bytes)
        if ($value->getSize() > 100 * 1024) {
            $this->failureReason = 'file size';
            return false;
        }

        // Check file dimensions
        $image = getimagesize($value->getPathname());
        if ($image[0] > 300 || $image[1] > 400) {
            $this->failureReason = 'dimensions';
            return false;
        }

        return true;
    }

    public function message()
    {
        switch ($this->failureReason) {
            case 'file type':
                return 'The :attribute must be a JPG image.';
            case 'file size':
                return 'The :attribute must not exceed 100 KB in size.';
            case 'dimensions':
                return 'The :attribute must have a maximum width of 300px and a maximum height of 400px.';
            default:
                return 'The :attribute must be a JPG image with a maximum width of 300px and a maximum height of 400px, and must not exceed 100 KB in size.';
        }
    }
}
