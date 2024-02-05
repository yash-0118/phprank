<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('announcementGuest.content', 'Hello Guests');
        $this->migrator->add('announcementGuest.type', 'primary');
    }
};
