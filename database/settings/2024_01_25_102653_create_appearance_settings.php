<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appearance.logo', 'default\3VcahcZajeQxzn6fkJfekCrZeTjdHDIIav6EPR7a.svg');
        $this->migrator->add('appearance.favicon', 'default\3VcahcZajeQxzn6fkJfekCrZeTjdHDIIav6EPR7a.svg');
        $this->migrator->add('appearance.theme', 'dark');
        $this->migrator->add('appearance.custom_css', '@import url("https://rsms.me/inter/inter.css");');
    }
};
