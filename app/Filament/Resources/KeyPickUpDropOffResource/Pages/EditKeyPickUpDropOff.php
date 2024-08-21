<?php

namespace App\Filament\Resources\KeyPickUpDropOffResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\KeyPickUpDropOffResource;

class EditKeyPickUpDropOff extends EditRecord
{
    protected static string $resource = KeyPickUpDropOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('module_names.key_mgmt.drop_off'))
            ->color('success')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['drop_off_user_id']))
            $data['drop_off_user_id'] = auth()->id();

        return $data;
    }
}
