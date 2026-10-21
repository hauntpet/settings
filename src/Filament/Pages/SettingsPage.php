<?php

namespace Haunt\Settings\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Haunt\Settings\Models\Setting;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Form;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\EmbeddedSchema;
use Haunt\Settings\Filament\Clusters\SettingsCluster;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;

class SettingsPage extends Page
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;

    protected static ?string $cluster = SettingsCluster::class;

    public ?array $data = [];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $settings = Setting::all()
            ->keyBy('key')
            ->transform(fn ($s) => ['value' => $s->value, 'rules' => collect($s->rules)->join(', ')]);

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components(
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
            );
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->disabled(! $this->canEdit())
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function canEdit(): bool
    {
        return true;
    }

    public static function getSettingField(string $key)
    {
        return TextInput::make("{$key}.value")
            ->label(Str::headline($key))
            ->helperText(fn ($get) => $get($key)['rules']);
    }
}
