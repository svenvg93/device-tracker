<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages;
use App\Models\Device;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected static ?string $navigationGroup = 'Data';

    protected static ?string $navigationLabel = 'Device Registration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Device Name')
                    ->required(),

                DatePicker::make('current_date')
                    ->label('Current Date')
                    ->default(now()->toDateString())
                    ->disabled(), // Optional: Disable to prevent changes, keeping it read-only

                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric() // Ensures only numbers are input
                    ->required(), // Optional: make it required if necessary

                Select::make('device_type')
                    ->label('Device Type')
                    ->options([
                        'Gateway' => 'Gateway',
                        'Access Point' => 'Access Point',
                        'Media Converter' => 'Media Converter',
                    ]),

                Select::make('network')
                    ->label('Network')
                    ->options([
                        'B2C' => 'B2C',
                        'B2B' => 'B2B',
                        'Mobile' => 'Mobile',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Device Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('device_type')
                    ->label('Device Type')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('network')
                    ->label('Network')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
        ];
    }
}
