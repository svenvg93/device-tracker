<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use App\Models\DeviceColor;
use Filament\Widgets\ChartWidget;

class AccessPointWeeklyChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Access Points on B2C';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '250px';

    public ?string $filter = '6_months';

    protected function getFilters(): ?array
    {
        return [
            'month' => 'Last month',
            '3_months' => 'Last 3 Months',
            '6_months' => 'Last 6 Months',
            'year' => 'Last Year',
            'all' => 'All Time',
        ];
    }

    protected function getData(): array
    {
        // Fetch device amounts based on the selected filter and device_type = 'Gateway'
        $deviceAmounts = Device::query()
            ->where('device_type', 'Access Point') // Filter by device type
            ->when($this->filter == 'week', function ($query) {
                $query->where('created_at', '>=', now()->subWeek());
            })
            ->when($this->filter == 'month', function ($query) {
                $query->where('created_at', '>=', now()->subMonth());
            })
            ->when($this->filter == '3_months', function ($query) {
                $query->where('created_at', '>=', now()->subMonths(3));
            })
            ->when($this->filter == '6_months', function ($query) {
                $query->where('created_at', '>=', now()->subMonths(6));
            })
            ->when($this->filter == 'year', function ($query) {
                $query->where('created_at', '>=', now()->subYear());
            })
            ->get()
            ->groupBy('name'); // Group by device name

        // Fetch colors for each device name from the DeviceColor model
        $deviceColors = DeviceColor::all()->pluck('color', 'device_name')->toArray();

        // Prepare datasets for each device
        $datasets = [];
        $labels = []; // Initialize an array to hold the labels (dates)

        foreach ($deviceAmounts as $deviceName => $amounts) {
            $datasets[] = [
                'label' => $deviceName,
                'data' => $amounts->map(fn ($item) => $item->amount)->values(),
                'borderColor' => $deviceColors[$deviceName] ?? '#000000', // Default color if not found
                'backgroundColor' => $deviceColors[$deviceName] ?? '#000000',
                'pointBackgroundColor' => $deviceColors[$deviceName] ?? '#000000',
                'fill' => false,
                'cubicInterpolationMode' => 'monotone',
                'tension' => 0.4,
            ];

            // Collect unique dates for labels
            foreach ($amounts as $amount) {
                $date = $amount->created_at->format('Y-m-d'); // Format the date
                if (! in_array($date, $labels)) {
                    $labels[] = $date; // Add unique dates
                }
            }
        }

        sort($labels); // Sort the labels to ensure the dates are in order

        return [
            'datasets' => $datasets,
            'labels' => $labels, // Use the unique dates for labels
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'x' => [ // Configure x-axis for date labels
                    'type' => 'time',
                    'time' => [
                        'unit' => 'day', // Group by days
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Date',
                    ],
                ],
                'y' => [
                    'beginAtZero' => false,
                    'title' => [
                        'display' => true,
                        'text' => 'Amount',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}