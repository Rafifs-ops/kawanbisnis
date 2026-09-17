<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandLogo(asset('kawanbisnis-logo.png'))
            ->brandLogoHeight('3rem')
            ->login()
            ->colors([
                'primary' => Color::hex('#0066AE'),
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'danger' => Color::Rose,
                'warning' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->renderHook(
                'panels::head.end',
                fn() => new HtmlString(
                    <<<'HTML'
<style>
    :root {
        --fi-body-bg: #0a1116 !important;
    }
    .dark {
        --fi-body-bg: #0a1116 !important;
    }
    /* Blue-dominant gradient for Filament admin */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        background: linear-gradient(160deg, #0a1116 0%, #081e8e 40%, #0f3fb0 65%, #6fc6fa 100%);
        opacity: 0.35;
    }
    /* Ensure sidebar stays solid */
    .fi-sidebar {
        background-color: #0a1116 !important;
    }
</style>
HTML
                )
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
