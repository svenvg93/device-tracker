<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceColorResource\Pages;
use App\Models\DeviceColor; // Make sure this model exists
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class DeviceColorResource extends Resource
{
    protected static ?string $model = DeviceColor::class;

    protected static ?string $navigationLabel = 'Device Colors';

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Data';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('device_name')
                ->required(),
            Forms\Components\TextInput::make('color')
                ->required()
                ->maxLength(7) // Assuming hex color
                ->placeholder('#FFFFFF'),
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
            'index' => Pages\ListDeviceColors::route('/'),
            'create' => Pages\CreateDeviceColor::route('/create'),
            'edit' => Pages\EditDeviceColor::route('/{record}/edit'),
        ];
    }
}
