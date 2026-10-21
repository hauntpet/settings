<?php

namespace Haunt\Settings\Filament\Pages;

use Filament\Forms\Components;
use Filament\Schemas\Schema;

class General extends SettingsPage
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
    }
}
