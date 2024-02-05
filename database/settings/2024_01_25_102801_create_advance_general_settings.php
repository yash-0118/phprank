<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('advance_general.demo_url', 'www.google.com');
        $this->migrator->add('advance_general.bad_words', 'nothing');
    }
};
