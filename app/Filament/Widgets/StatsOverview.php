<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\Location;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('company', Company::query()->count())->label(__('module_names.companies.plural_label'))
                ->description(' ')
                ->descriptionIcon('heroicon-o-building-office-2', IconPosition::Before)
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "goto('filament.admin.resources.companies.index')",
                ]),
            Stat::make('user', User::query()->count())->label(__('module_names.users.plural_label'))
                ->description(' ')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "goto('filament.admin.resources.users.index')",
                ]),
            Stat::make('location', Location::query()->count())->label(__('module_names.locations.plural_label'))
                ->description(' ')
                ->descriptionIcon('heroicon-o-map-pin', IconPosition::Before)
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "goto('filament.admin.resources.locations.index')",
                ]),
        ];
    }

    public function goto($routeName)
    {
        return redirect(route($routeName));
    }
}
