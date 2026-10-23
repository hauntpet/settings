<?php

namespace Haunt\Settings;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Haunt\Framework\Plugin\Concerns as PluginConcerns;
use Haunt\Framework\Plugin\HauntPlugin;
use Haunt\Settings\Filament\Pages;
use Haunt\Settings\Models\Setting;

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
        Setting::add('minimum_age', 13, ['required', 'integer', 'min:13']);
        Setting::add('state_game', 'alpha', ['required', 'in:alpha,beta,live']);
        Setting::add('state_login', 'open', ['required', 'in:open,closed,staff']);
        Setting::add('state_register', 'invite', ['required', 'in:open,closed,invite']);

        return $this;
    }
}
