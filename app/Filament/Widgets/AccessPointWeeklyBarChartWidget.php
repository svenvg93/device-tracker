<?php

namespace App\Filament\Widgets;

use App\Models\DeviceCounter;
use App\Models\DeviceModels;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class AccessPointWeeklyBarChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Total Access Points';

    protected int|string|array $columnSpan = 'half';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subWeek();
        $endDate = $this->filters['endDate'] ?? now();

        $startDate = Carbon::parse($startDate)->timezone(config('app.timezone'));
        $endDate = Carbon::parse($endDate)->timezone(config('app.timezone'));

        $deviceAmounts = DeviceCounter::query()
            ->where('device_type', 'Access Points')
            ->whereBetween('current_date', [$startDate, $endDate])
            ->get()
            ->groupBy('device_name') // Group by device_name
            ->sortKeys(); // Sort by device_name alphabetically

        $deviceColors = DeviceModels::all()->pluck('color', 'device_name')->toArray();

        $labels = collect($deviceAmounts)->flatten(1)
            ->pluck('current_date')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

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
                'stack' => true,
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
                'x' => [
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
        return 'bar';
    }
}
