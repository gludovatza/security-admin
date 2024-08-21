<?php

namespace App\Filament\Resources\KeyPickUpDropOffResource\Pages;

use App\Filament\Resources\KeyPickUpDropOffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeyPickUpDropOffs extends ListRecords
{
    protected static string $resource = KeyPickUpDropOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
