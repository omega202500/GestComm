<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class TranslationHelper
{
    public static function trans($key, $replace = [])
    {
        return __("messages.{$key}", $replace);
    }
}
