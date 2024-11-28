<?php

namespace App\Filament\Widgets;

use App\Models\DeviceCounter;
use App\Models\DeviceModels;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class GatewayWeeklyChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Gateways on B2C';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '250px';

    // Modify getData to use startDate and endDate from filters
    protected function getData(): array
    {
        // Ensure that startDate and endDate are passed as filters, falling back to defaults
        $startDate = $this->filters['startDate'] ?? now()->subWeek();
        $endDate = $this->filters['endDate'] ?? now();

        // Convert dates to the correct timezone without resetting the time
        $startDate = Carbon::parse($startDate)->timezone(config('app.timezone'));
        $endDate = Carbon::parse($endDate)->timezone(config('app.timezone'));

        // Fetch data with filtering based on the startDate and endDate
        $deviceAmounts = DeviceCounter::query()
            ->where('device_type', 'Gateway')
            ->where('device_network', 'B2C')
            ->whereBetween('current_date', [$startDate, $endDate])
            ->get()
            ->groupBy('device_name') // Group by device_name
            ->sortKeys(); // Sort by device_name alphabetically

        $deviceColors = DeviceModels::all()->pluck('color', 'device_name')->toArray();

        // Prepare the date labels (x-axis values)
        $labels = collect($deviceAmounts)->flatten(1)
            ->pluck('current_date')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Prepare datasets for each device
        $datasets = [];
        foreach ($deviceAmounts as $deviceName => $records) {
            $data = collect($labels)->map(function ($label) use ($records) {
                $record = $records->first(fn ($r) => $r->current_date && Carbon::parse($r->current_date)->format('Y-m-d') === $label);

                return $record ? $record->device_amount : null;
            });

            $datasets[] = [
                'label' => $deviceName,
                'data' => $data,
                'borderColor' => $deviceColors[$deviceName] ?? '#000000', // Color for the line
                'backgroundColor' => $deviceColors[$deviceName] ?? '#000000', // Color for the fill area
                'pointBackgroundColor' => $deviceColors[$deviceName] ?? '#000000', // Color for points on the line
                'fill' => false, // No fill under the line
                'cubicInterpolationMode' => 'monotone', // Smooth line
                'tension' => 0.4, // Smoother curve
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels, // Dates for x-axis
        ];
    }

    // Define chart options
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

    // Define chart type
    protected function getType(): string
    {
        return 'line';
    }
}
