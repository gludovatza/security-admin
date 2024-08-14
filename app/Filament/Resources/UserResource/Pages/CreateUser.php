<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use BezhanSalleh\FilamentShield\Support\Utils;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant()) &&
            auth()->user()->hasRole(Utils::getSuperAdminName())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        if( ! auth()->user()->hasRole(Utils::getSuperAdminName())) {
            $user = User::find($record->id);
            $user->assignRole('company_user');
            $user->companies()->attach(Filament::getTenant());
        }

        return $record;
    }
}
