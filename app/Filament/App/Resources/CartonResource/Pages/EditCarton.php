<?php

namespace App\Filament\App\Resources\CartonResource\Pages;

use App\Filament\App\Resources\CartonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarton extends EditRecord
{
    protected static string $resource = CartonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
