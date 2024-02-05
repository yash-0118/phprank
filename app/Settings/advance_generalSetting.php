<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class advance_generalSetting extends Settings
{
    public string $demo_url;
    public string $bad_words;

    public static function group(): string
    {
        return 'advance_general';
    }
}
