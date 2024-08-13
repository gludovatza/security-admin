<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditCompanyProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Company profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                // ...
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['slug'] = Str::slug($data['name']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.company.tenant');
    }
}
