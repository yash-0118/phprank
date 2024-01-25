<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class generalSetting extends Settings
{
    public string $title;
    public string $tagline;
    public int $custom_index;
    public int $results_per_page;
    public string $language;
    public string $timezone;
    public string $custom_js;

    public static function group(): string
    {
        return 'general';
    }
}
