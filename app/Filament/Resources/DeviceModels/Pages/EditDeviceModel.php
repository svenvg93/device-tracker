<?php

namespace App\Filament\Resources\DeviceModelsResource\Pages;

use App\Filament\Resources\DeviceModelsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeviceModels extends EditRecord
{
    protected static string $resource = DeviceModelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
