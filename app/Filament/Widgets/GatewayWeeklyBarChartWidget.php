<?php

namespace App\Filament\Widgets;

use App\Models\DeviceCounter;
use App\Models\DeviceModels;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class GatewayWeeklyBarChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Total Gateways';

    protected int|string|array $columnSpan = 'half';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subWeek();
        $endDate = $this->filters['endDate'] ?? now();

        $startDate = Carbon::parse($startDate)->timezone(config('app.timezone'));
        $endDate = Carbon::parse($endDate)->timezone(config('app.timezone'));

        $deviceAmounts = DeviceCounter::query()
            ->where('device_type', 'Gateway')
            ->whereBetween('current_date', [$startDate, $endDate])
            ->get()
            ->groupBy('device_name')
            ->sortKeys();

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
                'borderColor' => $deviceColors[$deviceName] ?? '#000000',
                'backgroundColor' => $deviceColors[$deviceName] ?? '#000000',
                'pointBackgroundColor' => $deviceColors[$deviceName] ?? '#000000',
                'fill' => false,
                'cubicInterpolationMode' => 'monotone',
                'tension' => 0.4,
                'stack' => true,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
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

    protected function getType(): string
    {
        return 'bar';
    }
}
