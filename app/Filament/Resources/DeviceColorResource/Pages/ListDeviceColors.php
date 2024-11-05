<?php

namespace App\Filament\Resources\DeviceColorResource\Pages;

use App\Filament\Resources\DeviceColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeviceColors extends ListRecords
{
    protected static string $resource = DeviceColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
