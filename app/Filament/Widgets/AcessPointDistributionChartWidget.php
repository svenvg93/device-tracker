<?php

namespace App\Filament\Widgets;

use App\Models\DeviceCounter;
use App\Models\DeviceModels; // Import the DeviceColor model
use Filament\Widgets\ChartWidget;

class AcessPointDistributionChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Latest Access Point Distribution';

    protected int|string|array $columnSpan = 'half';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        // Get the last record for each device type
        $latestDevices = DeviceCounter::query()
            ->where('device_type', 'Access Points')
            ->latest('created_at') // Order by the created_at timestamp
            ->get()
            ->unique('device_name'); // Ensure only the last record per device name

        // Map to get the amounts for the latest devices
        $deviceAmounts = $latestDevices->pluck('device_amount', 'device_name'); // Adjust 'amount' if necessary

        // Fetch colors for each device name from the DeviceColor model
        $deviceColors = DeviceModels::all()->pluck('color', 'device_name')->toArray();

        // Calculate total amount
        $totalAmount = $deviceAmounts->sum();

        // Prepare datasets for pie chart
        $datasets = [
            'data' => $deviceAmounts->map(function ($amount) use ($totalAmount) {
                return ($totalAmount > 0) ? ($amount / $totalAmount) * 100 : 0;
            })->values()->toArray(),
            'backgroundColor' => $deviceAmounts->map(function ($amount, $deviceName) use ($deviceColors) {
                return $deviceColors[$deviceName] ?? '#000000'; // Default color if not found
            })->values()->toArray(),
        ];

        // Prepare labels
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
                    'position' => 'right', // Set legend position to the right
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'display' => false, // Hide x-axis ticks
                    ],
                    'grid' => [
                        'display' => false, // Hide x-axis grid lines
                    ],
                ],
                'y' => [
                    'ticks' => [
                        'display' => false, // Hide y-axis ticks
                    ],
                    'grid' => [
                        'display' => false, // Hide y-axis grid lines
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Use 'pie' chart type
    }
}
