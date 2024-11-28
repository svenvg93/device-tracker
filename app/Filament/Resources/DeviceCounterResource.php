<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceCounterResource\Pages;
use App\Models\DeviceCounter;
use App\Models\DeviceModels;
use App\Models\DeviceType;
use App\Models\Network;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeviceCounterResource extends Resource
{
    protected static ?string $model = DeviceCounter::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationLabel = 'Device Count Registration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('device_name') // Store device name directly
                    ->label('Device Name')
                    ->options(
                        DeviceModels::pluck('device_name', 'device_name')->toArray() // device_name as both key and value
                    )
                    ->searchable()
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
                    ->options(
                        DeviceType::pluck('type', 'type')->toArray() // Fetch device names as options
                    )
                    ->searchable() // Optional: Makes the dropdown searchable
                    ->required(), // Optional: Enforce selection
                Select::make('device_network')
                    ->label('Network')
                    ->options(
                        Network::pluck('name', 'name')->toArray() // Fetch device names as options
                    )
                    ->searchable() // Optional: Makes the dropdown searchable
                    ->required(), // Optional: Enforce selection
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('device_name')  // Access 'device_name' via the relationship
                ->label('Device Name')
                ->sortable()
                ->searchable(),
    
                TextColumn::make('device_type')  // Display the related 'name' from the DeviceType model
                    ->label('Device Type')
                    ->sortable()
                    ->searchable(),
    
                TextColumn::make('device_network')  // Display the related 'name' from the Network model
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
            'index' => Pages\ListDeviceCounter::route('/'),
            'create' => Pages\CreateDeviceCounter::route('/create'),
            'edit' => Pages\EditDeviceCounter::route('/{record}/edit'),
        ];
    }
}
