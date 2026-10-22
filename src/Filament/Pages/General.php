<?php

namespace Haunt\Settings\Filament\Pages;

use Filament\Schemas\Schema;
use Haunt\Framework\Facades\Hook;

class General extends SettingsPage
{
    public function form(Schema $schema): Schema
    {
        $settings = Hook::applyFilters('admin.settings.general', [
            self::getSettingField('state_game')
                ->label('Game State')
                ->required(),
            self::getSettingField('state_login')
                ->label('Login State')
                ->required(),
            self::getSettingField('state_register')
                ->label('Register State')
                ->required(),
        ]);

        return $schema
            ->components($settings);
    }
}
