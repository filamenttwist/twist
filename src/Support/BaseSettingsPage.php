<?php

namespace Obelaw\Twist\Support;

use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

/**
 * BaseSettingsPage
 */
abstract class BaseSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'obelaw-twist::layout.settings';

    /**
     * The header for save action.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Save')
                ->icon('heroicon-o-check')
                ->action(fn() => $this->save($this->validate())),
        ];
    }
}
