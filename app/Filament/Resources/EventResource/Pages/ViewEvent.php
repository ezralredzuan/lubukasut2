<?php

namespace App\Filament\Resources\EventResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Forms\Components\Placeholder;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}


