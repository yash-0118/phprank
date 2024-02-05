<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appearance.logo', 'default/sk33lBTdJBSH24lCddJtEVQGGBqD7Kli180AXLQI.svg');
        $this->migrator->add('appearance.favicon', 'default/sk33lBTdJBSH24lCddJtEVQGGBqD7Kli180AXLQI.svg');
        $this->migrator->add('appearance.theme', 'light');
        $this->migrator->add('appearance.custom_css', '@import url("https://rsms.me/inter/inter.css");');
    }
};
