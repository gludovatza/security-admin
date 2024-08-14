<?php

namespace App\Filament\Resources\LocationResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\LocationResource;

class CreateLocation extends CreateRecord
{
    protected static string $resource = LocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['company_id'])) // company_admin hozza létre
        {
            $data['company_id'] = Filament::getTenant()->id;
        }

        return $data;
    }
}
