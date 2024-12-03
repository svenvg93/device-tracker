<?php

namespace App\Filament\Widgets;

use App\Models\DeviceCounter;
use App\Models\DeviceModels; // Import the DeviceColor model
use Filament\Widgets\ChartWidget;

class GatewayDistributionChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Current Overall Gateway Distribution';

    protected int|string|array $columnSpan = 'half';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $latestDevices = DeviceCounter::query()
            ->where('device_type', 'Gateway')
            ->latest('created_at')
            ->get()
            ->unique('device_name');

        $deviceAmounts = $latestDevices->pluck('device_amount', 'device_name');

        $deviceColors = DeviceModels::all()->pluck('color', 'device_name')->toArray();

        $totalAmount = $deviceAmounts->sum();

        $datasets = [
            'data' => $deviceAmounts->map(function ($amount) use ($totalAmount) {
                return ($totalAmount > 0) ? ($amount / $totalAmount) * 100 : 0;
            })->values()->toArray(),
            'backgroundColor' => $deviceAmounts->map(function ($amount, $deviceName) use ($deviceColors) {
                return $deviceColors[$deviceName] ?? '#000000';
            })->values()->toArray(),
            'borderColor' => $deviceAmounts->map(function ($amount, $deviceName) use ($deviceColors) {
                return $deviceColors[$deviceName] ?? '#000000';
            })->values()->toArray(),
        ];

        $labels = $deviceAmounts->keys()->toArray();

        return [
            'datasets' => [$datasets],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'display' => false,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'ticks' => [
                        'display' => false,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
