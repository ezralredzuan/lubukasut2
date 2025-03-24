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
                ->after(function (Model $record) {
                    $this->redirect(route('filament.admin.resources.events.edit', $record->id));
                }),
        ];
    }
}
