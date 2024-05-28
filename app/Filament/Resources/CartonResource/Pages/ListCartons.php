<?php

namespace App\Filament\Resources\CartonResource\Pages;

use Filament\Actions;
use Action\ButtonAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CartonResource;

class ListCartons extends ListRecords
{
    protected static string $resource = CartonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\CreateAction::make(),

        ];
    }
}
