<?php

namespace Haunt\Settings\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class SettingsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'haunt/settings';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverClusters(
                in: __DIR__.'/Filament/Clusters',
                for: 'Haunt\\Settings\\Filament\\Clusters'
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
