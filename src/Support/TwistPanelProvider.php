<?php

namespace Obelaw\Twist\Support;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Str;
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
        $twist = new TwistClass;

        $this->twist($twist);

        $this->twist = $twist;

        Filament::registerPanel(
            fn(): Panel => $this->panel(Panel::make()),
        );
    }

    public function panel(Panel $panel): Panel
    {
        $this->twist->setPanel($panel);

        try {
            if (!$this->twist->getDisloadSetupAddons()) {
                $this->twist->loadSetupAddons(panel: $this->twist->getPath());
            }
        } catch (\Exception $e) {
            //
        }

        if (count(config('twist.panels')) > 1)
            $panel->userMenuItems(array_map(function ($panel) {
                return MenuItem::make()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->label(Str::title(str_replace('-', ' ', $panel)))
                    ->url('/' . $panel)
                    ->openUrlInNewTab();
            }, config('twist.panels')));

        if ($this->twist->getUploadDirectory())
            Twist::setUploadDirectory($this->twist->getUploadDirectory());

        return $panel
            ->id('obelaw-twist-' . $this->twist->getPath())
            ->sidebarCollapsibleOnDesktop()
            ->domain($this->twist->getDomain())
            ->path($this->twist->getPath())
            ->brandLogo($this->twist->getLogo())
            ->colors([
                'primary' => $this->twist->getColor(),
            ])
            ->plugins($this->twist->getAddons())
            ->middleware($this->twist->getMiddlewares())
            ->authMiddleware([
                Authenticate::class,
            ])
            ->maxContentWidth(MaxWidth::Full);
    }
}
