<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('announcementUser.content', 'Hello Users');
        $this->migrator->add('announcementUser.type', 'danger');
    }
};
