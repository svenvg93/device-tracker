<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AccessPointWeeklyBarChartWidget;
use App\Filament\Widgets\AccessPointWeeklyChartWidget;
use App\Filament\Widgets\AcessPointDistributionChartWidget;
use App\Filament\Widgets\DeviceTypeStatsWidget;
use App\Filament\Widgets\GatewayDistributionChartWidget;
use App\Filament\Widgets\GatewayWeeklyBarChartWidget;
use App\Filament\Widgets\GatewayWeeklyChartWidget;
use App\Forms\Components\DateFilterForm;
use App\Models\DeviceModels;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('registerDevice')
                ->form([
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
                    Select::make('device_network')
                        ->label('Network')
                        ->options([
                            'B2C' => 'B2C',
                            'B2B' => 'B2B',
                        ])
                        ->native(false)
                        ->required(),
                    TextInput::make('device_amount')
                        ->label('Amount')
                        ->numeric()
                        ->required(),
                    TextInput::make('device_type')
                        ->label('Device Type')
                        ->readonly(),
                    DatePicker::make('current_date')
                        ->label('Current Date')
                        ->default(now()->toDateString()),
                ])
                ->action(function (array $data) {
                    \App\Models\DeviceCounter::create([
                        'device_name' => $data['device_name'],
                        'current_date' => now()->toDateString(),
                        'device_amount' => $data['device_amount'],
                        'device_type' => $data['device_type'],
                        'device_network' => $data['device_network'],
                    ]);
                    Notification::make()
                        ->title('Device amount add Successfully')
                        ->success()
                        ->send();
                })
                ->modalHeading('Add New Device Amount')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('Add')
                ->button()
                ->color('primary')
                ->label('Add Device Amount')
                ->icon('heroicon-c-document-arrow-up')
                ->iconPosition('before')
                ->hidden(! auth()->user()->is_admin),
        ];
    }

    public function filtersForm(Form $form): Form
    {
        return DateFilterForm::make($form);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DeviceTypeStatsWidget::make(),
            GatewayDistributionChartWidget::make(),
            AcessPointDistributionChartWidget::make(),
        ];
    }

    public function getWidgets(): array
    {
        return [
            GatewayWeeklyBarChartWidget::make(),
            AccessPointWeeklyBarChartWidget::make(),
            GatewayWeeklyChartWidget::make(),
            AccessPointWeeklyChartWidget::make(),
        ];
    }
}
