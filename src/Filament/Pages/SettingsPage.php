<?php

namespace Haunt\Settings\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Haunt\Framework\Facades\Hook;
use Haunt\Settings\Models\Setting;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Form;
use Haunt\Settings\SettingsConstants;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Illuminate\Validation\ValidationException;
use Filament\Schemas\Components\EmbeddedSchema;
use Haunt\Framework\Extend\Filament\ActionHandler;
use Haunt\Settings\Filament\Clusters\SettingsCluster;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        Hook::doAction(SettingsConstants::SETTINGS_MOUNT, $this);
    }

    protected function fillForm(): void
    {
        $settings = Setting::all()
            ->keyBy('key')
            ->transform(fn ($s) => ['value' => $s->value, 'rules' => collect($s->rules)->join(', ')]);

        $data = Hook::applyFilters(SettingsConstants::SETTINGS_DATA, $this->mutateFormDataBeforeFill($settings->toArray()));

        $this->form->fill($data);
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
                    ->footer([
                        $this->getFormActions(),
                    ])
            );
    }

    public function getFormActions(): Actions
    {
        return Actions::make([
            $this->getSaveFormAction(),
        ]);
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Save Changes')
            ->action(fn () => $this->save())
            ->visible($this->canEdit());
    }

    public function save(): void
    {
        if (! $this->canEdit()) {
            return;
        }

        Hook::addAction(SettingsConstants::SETTINGS_SAVE, function ($page) {
            $state = true;
            collect($page->form->getState())->each(function ($s, $key) use (&$state) {
                try {
                    $setting = Setting::where('key', $key)->first();
                    $setting->set($s['value']);
                } catch(ModelNotFoundException) {
                    ActionHandler::errorNotification("Could not locate the {$key} setting.");
                    $state = false;
                } catch(ValidationException $e) {
                    ActionHandler::errorNotification("{$key}: {$e->getMessage()}");
                    $state = false;
                }
            });
            return $state;
        }, 10);
        $state = Hook::doAction(SettingsConstants::SETTINGS_SAVE, $this);
        if($state) {
            ActionHandler::successNotification('Successfully saved changes.');
        }
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
