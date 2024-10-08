<?php

namespace App\Providers\Filament;

use App\Filament;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Company;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Pages\Tenancy\EditCompanyProfile;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class CompanyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('company')
            ->path('company')
            ->login()
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                Filament\Pages\CompanyDashboardPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Company/Widgets'), for: 'App\\Filament\\Company\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandName(fn() => (! auth()->user()) ? 'Security Admin' : auth()->user()->companies()->first()->name)
            ->tenant(Company::class, slugAttribute: 'slug')
            ->tenantProfile(EditCompanyProfile::class)
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                function (): View {
                    $url_parts = explode('/', url()->previous()); // pl.: localhost:8000/company/first-company
                    $slug = end($url_parts);
                    $company = Company::where('slug', $slug)->first();
                    return view('company', [
                        'name' => $company?->name,
                        'logo' => $company?->logo,
                    ]);
                },
            )
            ->userMenuItems([
                'role' => MenuItem::make()->label(fn() => __('module_names.roles.label') . ': '. auth()->user()->roles->first()->name),
            ]);
    }
}
