<?php

namespace Obelaw\Obridge\Filament\Resources\ObridgeResource;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Obelaw\Obridge\Filament\Resources\ObridgeResource;

class ListObridge extends ListRecords
{
    protected static string $resource = ObridgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
