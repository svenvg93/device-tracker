<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AccessPointWeeklyChartWidget;
use App\Filament\Widgets\AcessPointDistributionChartWidget;
use App\Filament\Widgets\GatewayDistributionChartWidget;
use App\Filament\Widgets\GatewayWeeklyChartWidget;
use App\Models\DeviceModels;
use App\Models\DeviceType;
use App\Models\Network;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('registerDevice')
                ->form([
                    Select::make('name')
                        ->label('Device Name')
                        ->options(
                            DeviceModels::pluck('device_name', 'id')->toArray() // Fetch device names as options
                        )
                        ->searchable()
                        ->required(),
                    DatePicker::make('current_date')
                        ->label('Current Date')
                        ->default(now()->toDateString())
                        ->disabled(),
                    TextInput::make('amount')
                        ->label('Amount')
                        ->numeric()
                        ->required(),
                    Select::make('device_type')
                        ->label('Device Type')
                        ->options(
                            DeviceType::pluck('type', 'id')->toArray() // Fetch device names as options
                        )
                        ->searchable()
                        ->required(),
                    Select::make('network')
                        ->label('Network')
                        ->options(
                            Network::pluck('name', 'id')->toArray() // Fetch device names as options
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    \App\Models\DeviceCounter::create([
                        'name' => $data['name'],
                        'current_date' => now()->toDateString(),
                        'amount' => $data['amount'],
                        'device_type' => $data['device_type'],
                        'network' => $data['network'],
                    ]);
                    Notification::make()
                        ->title('Device Registered Successfully')
                        ->success()
                        ->send();
                })
                ->modalHeading('Register New Device')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('Register')
                ->button()
                ->color('primary')
                ->label('Register Device')
                ->icon('heroicon-c-document-arrow-up')
                ->iconPosition('before'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GatewayDistributionChartWidget::make(),
            AcessPointDistributionChartWidget::make(),
            GatewayWeeklyChartWidget::make(),
            AccessPointWeeklyChartWidget::make(),
        ];
    }
}
