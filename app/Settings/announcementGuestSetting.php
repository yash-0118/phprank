<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class announcementGuest extends Settings
{
    public string $content;
    public string $type;

    public static function group(): string
    {
        return 'announcementGuest';
    }
}
