<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('announcement_guest.content', 'Hello Guests');
        $this->migrator->add('announcement_guest.type', 'primary');
    }
};
