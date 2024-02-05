<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('advanceGeneral.demo_url', 'www.google.com');
        $this->migrator->add('advanceGeneral.bad_words', 'nothing');
    }
};
