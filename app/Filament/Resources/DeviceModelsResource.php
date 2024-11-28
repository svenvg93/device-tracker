<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceModelsResource\Pages;
use App\Models\DeviceModels;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class DeviceModelsResource extends Resource
{
    protected static ?string $model = DeviceModels::class;

    protected static ?string $navigationLabel = 'Device Models';

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected static ?string $navigationGroup = 'Data';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('device_name')
                ->required(),
            Forms\Components\TextInput::make('color')
                ->required()
                ->maxLength(7) // Assuming hex color
                ->placeholder('#FFFFFF')
                ->regex('/^#[0-9A-Fa-f]{6}$/', 'The color must be a valid hex code (e.g., #FFFFFF).'), // Regex validation for hex color
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_name')->sortable(), // Corrected class
                Tables\Columns\TextColumn::make('color')->sortable(), // Corrected class
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
