<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditCompanyProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return __('module_names.company_profile');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo')->label(__('fields.logo'))
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->preserveFilenames()
                    ->openable()
                    ->downloadable()
                    ->maxSize(20000),
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
