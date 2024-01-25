<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.facebook', 'www.facebook.com');
        $this->migrator->add('social.youtube', 'www.youtube.com');
        $this->migrator->add('social.twitter', 'www.twitter.com');
        $this->migrator->add('social.instagram', 'www.instagram.com');
    }
};
