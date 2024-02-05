<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class advance_crawlerSetting extends Settings
{
    public string $user_agent;
    public string $proxies;
    public int $links_per_sitemaps;

    public static function group(): string
    {
        return 'advance_crawl';
    }
}
