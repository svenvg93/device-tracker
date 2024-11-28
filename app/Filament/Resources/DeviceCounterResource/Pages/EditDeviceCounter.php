<?php

namespace App\Filament\Resources\DeviceCounterResource\Pages;

use App\Filament\Resources\DeviceCounterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeviceCounter extends EditRecord
{
    protected static string $resource = DeviceCounterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
