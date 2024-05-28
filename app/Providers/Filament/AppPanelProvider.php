<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use app\Models\User;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Pages\Dashboard;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\OrderResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\CustomerResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->userMenuItems([
                MenuItem::make()
                    ->label('Admin')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url('/admin')
                    ->visible(function (): bool {
                        $user = Auth::user();
                        return $user && $user->email === 'muliawandev@gmail.com';
                    }),

            ])
            ->sidebarCollapsibleOnDesktop(true)
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'primary' => Color::Amber,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->brandLogo(asset('images/packing-list.svg'))
            // ->brandLogoHeight('2rem')
            ->favicon(asset('images/packing-list.png'))
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
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
                //     $navigationGroups[] = NavigationGroup::make('Setting')
                //         ->items([
                //             ...UserResource::getNavigationItems(),
                //             NavigationItem::make('Roles')
                //                 ->icon('heroicon-o-user-group')
                //                 ->isActiveWhen(fn (): bool => request()->routeIs([
                //                     'filament.admin.resources.roles.index',
                //                     'filament.admin.resources.roles.create',
                //                     'filament.admin.resources.roles.view',
                //                     'filament.admin.resources.roles.edit',
                //                 ]))
                //                 ->url(fn (): string => route('filament.admin.resources.roles.index')),
                //             NavigationItem::make('Permissions')
                //                 ->icon('heroicon-o-lock-closed')
                //                 ->isActiveWhen(fn (): bool => request()->routeIs([
                //                     'filament.admin.resources.permissions.index',
                //                     'filament.admin.resources.permissions.create',
                //                     'filament.admin.resources.permissions.view',
                //                     'filament.admin.resources.permissions.edit',
                //                 ]))
                //                 ->url(fn (): string => route('filament.admin.resources.permissions.index')),
                //         ]);
                // }

                return $builder->groups($navigationGroups);
            });
    }
}
