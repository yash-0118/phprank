<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.facebook', 'https://www.facebook.com/aaa');
        $this->migrator->add('social.youtube', 'https://www.youtube.com/watch?v=1oGrDyFp9X8&t=2839s');
        $this->migrator->add('social.twitter', 'https://www.twitter.com/123');
        $this->migrator->add('social.instagram', 'https://www.instagram.com/username');
    }
};
