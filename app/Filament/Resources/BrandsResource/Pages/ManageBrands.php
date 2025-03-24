<?php

namespace App\Filament\Resources\BrandsResource\Pages;

use App\Filament\Resources\BrandsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBrands extends ManageRecords
{
    protected static string $resource = BrandsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
