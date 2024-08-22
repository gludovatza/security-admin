<?php

namespace App\Filament\Resources\KeyPickUpDropOffResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\KeyPickUpDropOffResource;
use App\Models\Key;

class CreateKeyPickUpDropOff extends CreateRecord
{
    protected static string $resource = KeyPickUpDropOffResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(!isset($data['company_id']))
            $data['company_id'] = Filament::getTenant()->id;

        if(!isset($data['pick_up_user_id']))
            $data['pick_up_user_id'] = auth()->id();

        Key::find($data['key_id'])->update(['available' => false]);

        return $data;
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label(__('module_names.key_mgmt.pick_up'))
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
