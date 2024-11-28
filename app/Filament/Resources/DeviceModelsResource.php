<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceModelsResource\Pages;
use App\Models\DeviceModels;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;

class DeviceModelsResource extends Resource
{
    protected static ?string $model = DeviceModels::class;

    protected static ?string $navigationLabel = 'Device Models';

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected static ?string $navigationGroup = 'Data';

    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('device_name')
                ->required(),
            Select::make('device_type')
                ->label('Device Type')
                ->options([
                    'Gateway' => 'Gateway',
                    'Access Points' => 'Access Points',
                    'Media Converter' => 'Media Converter',
                ])
                ->native(false)
                ->required(),
            Forms\Components\ColorPicker::make('color') // Use ColorPicker instead of TextInput
                ->required()
                ->label('Device Color') // Optional: Add a label
                ->placeholder('#FFFFFF'), // Optional: Placeholder for HEX value
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_name')
                    ->sortable()
                    ->label('Device Name'), // Optional: Add a label
                Tables\Columns\TextColumn::make('device_name')
                    ->sortable()
                    ->label('Device Name'), // Optional: Add a label
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color') // Optional: Add a label
                    ->sortable(), // Allow sorting by color field
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array // Use this method to define pages
    {
        return [
            'index' => Pages\ListDeviceModels::route('/'),
            'create' => Pages\CreateDeviceModels::route('/create'),
            'edit' => Pages\EditDeviceModels::route('/{record}/edit'),
        ];
    }
}
