<?php

namespace App\Filament\Resources\KeyResource\Pages;

use App\Filament\Resources\KeyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKey extends EditRecord
{
    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
