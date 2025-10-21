<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use TomatoPHP\FilamentTenancy\FilamentTenancyPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentColor::register([
            'primary' => Color::hex(config('filament-colors.primary.hex')),
            'secondary' => Color::hex(config('filament-colors.secondary.hex')),
            'indigo' => Color::hex(config('filament-colors.indigo.hex')),
            'success' => Color::hex(config('filament-colors.success.hex')),
            'danger' => Color::hex(config('filament-colors.danger.hex')),
            'warning' => Color::hex(config('filament-colors.warning.hex')),
            'darkextra' => Color::hex(config('filament-colors.darkextra.hex')),
            'yellow' => Color::hex(config('filament-colors.yellow.hex')),
        ]);

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex(config('filament-colors.primary.hex')),
            ])
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(asset('images/fondo_claro.svg'))
            ->darkModeBrandLogo(asset('images/fondo_oscuro.svg'))
            ->brandLogoHeight('3rem')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentTenancyPlugin::make()->panel('dashboard'),
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
            ]);
    }
}
