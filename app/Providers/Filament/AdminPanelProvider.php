<?php

namespace App\Providers\Filament;

use App\Filament\Pages\EditProfilePage;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Enums\UserMenuPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->homeUrl('/admin')
            ->userMenu(position: UserMenuPosition::Sidebar)
            ->userMenuItems([
                'profile' => fn (Action $action) => $action
                    ->label('Edit profile')
                    ->icon('heroicon-s-user')
                    ->url(fn (): string => EditProfilePage::getUrl()),
            ])
            ->defaultThemeMode(ThemeMode::Dark)
            ->darkMode()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            //->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->databaseNotifications()
            ->unsavedChangesAlerts()
            ->spa()
            ->navigationGroups([
                'Albums & Songs',
                'EPs & Songs',
                'Podcasts',
                'User Management',
                'System Management',
            ])
            ->navigationItems([
                NavigationItem::make('System Logs')
                    ->url('/log-viewer')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->group('System Management')
                    ->sort(50)
                    ->visible(fn () => auth()->user()?->hasRole('Admin')),
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                \App\Filament\Widgets\Admin\OverviewStatsWidget::class,
                \App\Filament\Widgets\Admin\TopSongsWidget::class,
                \App\Filament\Widgets\Admin\RevenueChartWidget::class,
                \App\Filament\Widgets\Admin\UserRegistrationChartWidget::class,
                \App\Filament\Widgets\Admin\ContentDistributionWidget::class,
                \App\Filament\Widgets\Admin\RecentActivityWidget::class,
                \App\Filament\Widgets\Admin\SubscriptionStatsWidget::class,
                \App\Filament\Widgets\Admin\TopAlbumsWidget::class,
                \App\Filament\Widgets\Admin\PaymentStatsWidget::class,
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
