<?php

namespace Haunt\Settings\Filament\Clusters;

use UnitEnum;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Haunt\Framework\FrameworkConstants;
use Filament\Pages\Enums\SubNavigationPosition;

class SettingsCluster extends Cluster
{
    protected static UnitEnum|string|null $navigationGroup = FrameworkConstants::APPLICATION_GROUP;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?int $navigationSort = 100;
    protected static ?string $slug = 'settings';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $title = 'Settings';
}
