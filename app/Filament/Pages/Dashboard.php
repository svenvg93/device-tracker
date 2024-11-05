<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AccessPointWeeklyChartWidget;
use App\Filament\Widgets\AcessPointDistributionChartWidget;
use App\Filament\Widgets\GatewayDistributionChartWidget;
use App\Filament\Widgets\GatewayWeeklyChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.dashboard';

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
