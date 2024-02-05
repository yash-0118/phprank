<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class announcementUser extends Settings
{
    public string $content;
    public string $type;

    public static function group(): string
    {
        return 'announcementUser';
    }
}
