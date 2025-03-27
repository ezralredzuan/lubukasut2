<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{

    protected static ?string $heading = 'Orders Over Time';

    protected static ?int $sort = 2; // Lower numbers appear first

    protected function getData(): array
    {
        $orders = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->values()->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
            'labels' => $orders->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
