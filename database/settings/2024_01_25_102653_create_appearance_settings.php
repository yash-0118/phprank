<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appearance.logo', 'uploads\5r8W06jrtw9vi8HjagqugcDu8CxTocTXJzC5w86q.png');
        $this->migrator->add('appearance.favicon', 'uploads\5r8W06jrtw9vi8HjagqugcDu8CxTocTXJzC5w86q.png');
        $this->migrator->add('appearance.theme', 'dark');
        $this->migrator->add('appearance.custom_css', '@import url("https://rsms.me/inter/inter.css");');
    }
};
