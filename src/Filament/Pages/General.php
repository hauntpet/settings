<?php

namespace Haunt\Settings\Filament\Pages;

use Filament\Schemas\Schema;
use Haunt\Framework\Facades\Hook;
use Haunt\Settings\SettingsConstants;

class General extends SettingsPage
{
    public function form(Schema $schema): Schema
    {
        $schema = Hook::applyFilters(SettingsConstants::SETTINGS_GENERAL_FORM, $schema);
        $fields = Hook::applyFilters(SettingsConstants::SETTINGS_GENERAL_FIELDS, [
            self::getSettingField('minimum_age')
                ->label('Minimum Age')
                ->required(),
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
            ->components($fields);
    }
}
