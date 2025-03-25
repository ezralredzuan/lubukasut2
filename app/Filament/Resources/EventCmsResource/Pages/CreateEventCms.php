<?php

namespace App\Filament\Resources\EventCmsResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventCms extends CreateRecord
{
    protected static string $resource = EventResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
