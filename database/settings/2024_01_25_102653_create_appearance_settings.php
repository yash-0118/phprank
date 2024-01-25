<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appearance.logo', 'PhpRank');
        $this->migrator->add('appearance.favicon', 'PhpRank');
        $this->migrator->add('appearance.theme', 'dark');
        $this->migrator->add('appearance.custom_css', '@import url("https://rsms.me/inter/inter.css");');
    }
};
