<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('advance_crawl.user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36');
        $this->migrator->add('advance_crawl.proxies', 'nothing');
        $this->migrator->add('advance_crawl.links_per_sitemaps', '5');
    }
};
