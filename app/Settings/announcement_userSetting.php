<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class announcement_user extends Settings
{
    public string $content;
    public string $type;

    public static function group(): string
    {
        return 'announcement_user';
    }
}
