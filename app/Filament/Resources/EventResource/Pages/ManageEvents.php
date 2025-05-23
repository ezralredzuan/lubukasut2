<?php

namespace App\Filament\Resources\EventResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\EventResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\CreateAction;


class ManageEvents extends ManageRecords
{
    protected static string $resource = EventResource::class;
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth('6xl') // ✅ Sets a wider modal for creating events
                ->after(function () {
                    session()->flash('clear_grapesjs', true);
                    $this->redirect(static::getResource()::getUrl('index'));
                }),
        ];
    }
}
