<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PassportSizePhoto implements Rule
{
    public function passes($attribute, $value)
    {
        // Check file size
        if ($value->getSize() > 100 * 1024) {
            return false;
        }

        // Check file dimensions
        $image = getimagesize($value->getPathname());
        // if ($image[0] != 300 || $image[1] != 400) {
        //     return false;
        // }

        return true;
    }

    public function message()
    {
        return 'The :attribute must be a JPG image with dimensions of 300px width by 400px height and must not exceed 100 KB in size.';
    }
}
