<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.facebook', 'https://facebook.com/PHP/');
        $this->migrator->add('social.youtube', 'https://www.youtube.com/watch?v=bF04VPI68sg');
        $this->migrator->add('social.twitter', 'https://www.twitter.com/username');
        $this->migrator->add('social.instagram', 'https://www.instagram.com/username');
    }
};
