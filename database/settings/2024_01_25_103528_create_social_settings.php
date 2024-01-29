<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.facebook', 'https://www.facebook.com');
        $this->migrator->add('social.youtube', 'https://www.youtube.com');
        $this->migrator->add('social.twitter', 'https://www.twitter.com');
        $this->migrator->add('social.instagram', 'https://www.instagram.com');
    }
};
