<?php

namespace App\Filament\Resources\EventCmsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\EventResource;


class ListEventCms extends ManageRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            \Filament\Actions\CreateAction::make()
                ->after(function () {
                    $this->redirect(static::getResource()::getUrl('index'));
                }),
        ];
    }
}
