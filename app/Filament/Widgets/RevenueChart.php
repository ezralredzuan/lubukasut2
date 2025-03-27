<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Over Time';

    protected static ?int $sort = 2; // Lower numbers appear first

    protected function getData(): array
    {
        $revenue = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (RM)',
                    'data' => $revenue->values()->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
            ],
            'labels' => $revenue->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
