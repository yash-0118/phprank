<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.title', 'PhpRank');
        $this->migrator->add('general.tagline', 'PhpRank');
        $this->migrator->add('general.custom_index', '5');
        $this->migrator->add('general.result_per_page', '10');
        $this->migrator->add('general.language', 'English');
        $this->migrator->add('general.timezone', 'UTC');
        $this->migrator->add('general.custom_js', '<script>if(top != self){top.location.replace(document.location);}<script>');
    }
};
