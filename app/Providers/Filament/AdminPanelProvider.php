<?php
// app/Providers/Filament/AdminPanelProvider.php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\OrderResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\CustomerResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate; // Tambahkan ini
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            ->sidebarCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->userMenuItems([
                MenuItem::make()
                    ->label('Dashboard')
                    ->icon('heroicon-o-link')
                    ->url('/app')
            ])
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->brandLogo(asset('images/packing-list.svg'))
            // ->brandLogoHeight('2rem')
            ->favicon(asset('images/packing-list.png'))

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            // ->middleware([
            //     EncryptCookies::class,
            //     AddQueuedCookiesToResponse::class,
            //     StartSession::class,
            //     AuthenticateSession::class,
            //     ShareErrorsFromSession::class,
            //     VerifyCsrfToken::class,
            //     SubstituteBindings::class,
            //     DisableBladeIconComponents::class,
            //     DispatchServingFilamentEvent::class,
            // ])
            // ->authMiddleware([
            //     Authenticate::class,
            // ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $navigationGroups = [
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    NavigationGroup::make('Source')
                        ->items([
                            ...CustomerResource::getNavigationItems(),
                            ...ProductResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Packing List')
                        ->items([
                            ...OrderResource::getNavigationItems(),
                        ]),
                ];

                // Periksa izin menggunakan Gate
                // if (Gate::allows('view-settings')) {
                $navigationGroups[] = NavigationGroup::make('Setting')
                    ->items([
                        ...UserResource::getNavigationItems(),
                        NavigationItem::make('Roles')
                            ->icon('heroicon-o-user-group')
                            ->isActiveWhen(fn (): bool => request()->routeIs([
                                'filament.admin.resources.roles.index',
                                'filament.admin.resources.roles.create',
                                'filament.admin.resources.roles.view',
                                'filament.admin.resources.roles.edit',
                            ]))
                            ->url(fn (): string => route('filament.admin.resources.roles.index')),
                        NavigationItem::make('Permissions')
                            ->icon('heroicon-o-lock-closed')
                            ->isActiveWhen(fn (): bool => request()->routeIs([
                                'filament.admin.resources.permissions.index',
                                'filament.admin.resources.permissions.create',
                                'filament.admin.resources.permissions.view',
                                'filament.admin.resources.permissions.edit',
                            ]))
                            ->url(fn (): string => route('filament.admin.resources.permissions.index')),
                    ]);
                // }

                return $builder->groups($navigationGroups);
            });
    }
}
