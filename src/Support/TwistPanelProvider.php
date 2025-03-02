<?php

namespace Obelaw\Twist\Support;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\MaxWidth;
use Obelaw\Permit\PermitPlugin;
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
            fn(): Panel => $this->panel(Panel::make()),
        );
    }

    public function panel(Panel $panel): Panel
    {
        $this->twist->setPanel($panel);
        $this->twist->loadSetupAddons();

        return $panel
            ->id('obelaw-twist')
            ->sidebarCollapsibleOnDesktop()
            ->domain($this->twist->getDomain())
            ->path($this->twist->getPath())
            ->brandLogo(fn() => view('obelaw-twist::layout.logo'))
            ->colors([
                'primary' => '#fc4706',
            ])
            ->plugins($this->twist->getAddons())
            ->plugin(PermitPlugin::make())
            ->middleware($this->twist->getMiddlewares())
            ->authMiddleware([
                Authenticate::class,
            ])
            ->maxContentWidth(MaxWidth::Full);
    }
}
