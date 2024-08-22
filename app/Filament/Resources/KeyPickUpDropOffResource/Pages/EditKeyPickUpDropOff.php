<?php

namespace App\Filament\Resources\KeyPickUpDropOffResource\Pages;

use App\Models\Key;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!isset($data['drop_off_user_id']))
            $data['drop_off_user_id'] = auth()->id();
        $record->update($data);

        Key::find($record->key_id)->update(['available' => true]);

        return $record;
    }
}
