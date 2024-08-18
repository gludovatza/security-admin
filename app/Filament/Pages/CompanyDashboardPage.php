<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Dashboard as AdminDashboard;

class CompanyDashboardPage extends AdminDashboard
{
    protected static string $view = 'filament.pages.company-dashboard-page';

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ??
            static::$title ??
            __('filament-panels::pages/dashboard.title');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return static::$navigationIcon
            ?? FilamentIcon::resolve('panels::pages.dashboard.navigation-item')
            ?? (Filament::hasTopNavigation() ? 'heroicon-m-home' : 'heroicon-o-home');
    }

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }

    public function getWidgets(): array
    {
        return Filament::getWidgets();
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('filament-panels::pages/dashboard.title');
    }
}
