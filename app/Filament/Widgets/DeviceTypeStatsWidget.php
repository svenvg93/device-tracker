<?php

namespace App\Filament\Widgets;

use App\Models\DeviceCounter;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DeviceTypeStatsWidget extends BaseWidget
{
    protected function getPollingInterval(): ?string
    {
        return '10s'; // Adjust as needed
    }

    public function getSubheading(): ?string
    {
        return 'Current Numbers';
    }

    protected function getCards(): array
    {
        // Define the current and previous week ranges
        $endDate = now();
        $startDate = $endDate->clone()->subWeek();
        $prevWeekStartDate = $startDate->clone()->subWeek();
        $prevWeekEndDate = $startDate;

        // Fetch totals for the current and previous weeks grouped by device_type
        $currentTotals = DeviceCounter::query()
            ->selectRaw('device_type, SUM(device_amount) as total')
            ->where('current_date', '>=', $startDate)
            ->groupBy('device_type')
            ->pluck('total', 'device_type');

        $previousTotals = DeviceCounter::query()
            ->selectRaw('device_type, SUM(device_amount) as total')
            ->whereBetween('current_date', [$prevWeekStartDate, $prevWeekEndDate])
            ->groupBy('device_type')
            ->pluck('total', 'device_type');

        // Fetch grand totals for all devices
        $currentTotalDevices = $currentTotals->sum();
        $previousTotalDevices = $previousTotals->sum();

        $totalDifference = $currentTotalDevices - $previousTotalDevices;
        $totalPercentageChange = $previousTotalDevices > 0
            ? round(($totalDifference / $previousTotalDevices) * 100, 2)
            : null;

        // Add a stat for the total devices
        $stats = [
            Stat::make('Total Devices', $currentTotalDevices)
                ->description($totalPercentageChange !== null
                    ? ($totalDifference >= 0
                        ? "+{$totalPercentageChange}% increase"
                        : "{$totalPercentageChange}% decrease")
                    : 'No previous data')
                ->descriptionIcon($totalDifference >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($totalDifference >= 0 ? 'success' : 'danger'),
        ];

        // Add stats for each device_type
        foreach ($currentTotals as $deviceType => $currentTotal) {
            $previousTotal = $previousTotals[$deviceType] ?? 0;
            $difference = $currentTotal - $previousTotal;
            $percentageChange = $previousTotal > 0
                ? round(($difference / $previousTotal) * 100, 2)
                : null;

            $stats[] = Stat::make(ucfirst($deviceType), $currentTotal)
                ->description($percentageChange !== null
                    ? ($difference >= 0
                        ? "+{$percentageChange}% increase"
                        : "{$percentageChange}% decrease")
                    : 'No previous data')
                ->descriptionIcon($difference >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($difference >= 0 ? 'success' : 'danger');
        }

        return $stats;
    }
}
