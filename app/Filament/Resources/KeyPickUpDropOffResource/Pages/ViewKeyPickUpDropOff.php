<?php

namespace App\Filament\Resources\KeyPickUpDropOffResource\Pages;

use App\Filament\Resources\KeyPickUpDropOffResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKeyPickUpDropOff extends ViewRecord
{
    protected static string $resource = KeyPickUpDropOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->visible(fn($record) => $record->drop_off_time == null),
        ];
    }
}
