<?php

namespace Haunt\Settings;

use Haunt\Framework\Plugin\Concerns as PluginConcerns;
use Haunt\Framework\Plugin\HauntPlugin;

class SettingsPlugin extends HauntPlugin
{
    use PluginConcerns\HasFilamentPlugin;
    use PluginConcerns\HasMigrations;

    public function install(): bool
    {
        return true;
    }

    public function uninstall(): bool
    {
        return true;
    }

    public function filamentPlugin(): string
    {
        return 'Haunt\\Settings\\Filament\\SettingsPlugin';
    }
}
