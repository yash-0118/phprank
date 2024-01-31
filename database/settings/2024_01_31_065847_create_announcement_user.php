<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('announcement_user.content', 'Hello Users');
        $this->migrator->add('announcement_user.type', 'danger');
    }
};
