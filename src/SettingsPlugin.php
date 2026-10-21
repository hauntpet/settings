<?php

namespace Haunt\Settings;

use Filament\Panel;
use Filament\Contracts\Plugin;
use Haunt\Settings\Filament\Pages;
use Haunt\Settings\Models\Setting;
use Haunt\Framework\Plugin\HauntPlugin;
use Haunt\Framework\Plugin\Concerns as PluginConcerns;

class SettingsPlugin extends HauntPlugin implements Plugin
{
    use PluginConcerns\HasAdminPlugin;
    use PluginConcerns\HasMigrations;

    public function getId(): string
    {
        return 'haunt/settings';
    }

    public function admin(Panel $panel): void
    {
        $panel
            ->discoverClusters(
                in: __DIR__.'/Filament/Clusters',
                for: 'Haunt\\Settings\\Filament\\Clusters'
            )
            // ->resources([
            //     PostResource::class,
            //     CategoryResource::class,
            // ])
            ->pages([
                Pages\General::class,
            ]);
    }

    public function install(): bool
    {
        $this->installSettings();

        return true;
    }

    public function uninstall(): bool
    {
        return true;
    }

    private function installSettings(): self
    {
        Setting::add('state_game', 'alpha', ['required', 'string']);
        Setting::add('state_login', 'open', ['required', 'string']);
        Setting::add('state_register', 'invite', ['required', 'string']);

        return $this;
    }
}
