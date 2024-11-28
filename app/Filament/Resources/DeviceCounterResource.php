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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeviceCounterResource extends Resource
{
    protected static ?string $model = DeviceCounter::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationLabel = 'Device Count Registration';

    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->is_admin;
    }

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
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $deviceType = DeviceModels::where('device_name', $state)->value('device_type');
                        $set('device_type', $deviceType);
                    }),
                TextInput::make('device_type')
                    ->label('Device Type')
                    ->readonly(),
                TextInput::make('device_amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(),
                Select::make('device_network')
                    ->label('Network')
                    ->options(
                        Network::pluck('name', 'name')->toArray())
                    ->searchable()
                    ->required(),
                DatePicker::make('current_date')
                    ->label('Current Date')
                    ->default(now()->format('d-m-Y'))  // Ensure it's in the correct format
                    ->readonly(),
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
                TextColumn::make('device_amount')
                    ->label('Amount')
                    ->sortable(),
                TextColumn::make('current_date')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('device_name')
                    ->multiple()
                    ->options(DeviceCounter::pluck('device_name', 'device_name')->toArray()),
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
