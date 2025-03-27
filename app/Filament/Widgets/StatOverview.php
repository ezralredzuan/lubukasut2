<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Lower numbers appear first

    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count()),
            Stat::make('Total Revenue', 'RM' . number_format(Order::sum('price'), 2)),
            Stat::make('Total Customers', Customer::count()),
            Stat::make('Pending Orders', Order::where('payment_status', '0')->count())
                ->color('danger'),
        ];
    }
}
