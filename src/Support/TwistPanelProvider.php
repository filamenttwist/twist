<?php

namespace Obelaw\Twist\Support;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Obelaw\Permissions\Http\Middleware\PermissionMiddleware;
use Obelaw\Twist\Classes\TwistClass;
use Obelaw\Twist\Facades\Twist;

class TwistPanelProvider extends PanelProvider
{
    private TwistClass $twist;

    public function twist(TwistClass $twist)
    {
        //
    }

    public function register(): void
    {
        $twist = Twist::make();

        $this->twist($twist);

        $this->twist = $twist;

        Filament::registerPanel(
            fn (): Panel => $this->panel(Panel::make()),
        );
    }

    public function panel(Panel $panel): Panel
    {
        $this->twist->setPanel($panel);

        return $panel
            ->id('erp-o')
            ->path($this->twist->getPath())
            ->colors([
                'primary' => '#fc4706',
            ])
            ->plugins($this->twist->getModules())
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                PermissionMiddleware::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('obelaw');


            
    }
}
