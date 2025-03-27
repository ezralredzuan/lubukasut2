<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class RecentOrders extends TableWidget
{
    protected static ?string $heading = 'Recent Orders';

    protected static ?int $sort = 3; // Lower numbers appear first

    protected function getTableQuery(): Builder
    {
        return Order::query()->latest()->limit(5);
    }

    protected int|string|array $columnSpan = 'full';

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('reference_no')->label('Reference No'),
            Tables\Columns\TextColumn::make('customer.full_name')->label('Customer'),
            Tables\Columns\TextColumn::make('price')->label('Price')->prefix('RM '),
            Tables\Columns\TextColumn::make('payment_status')
                ->formatStateUsing(fn ($state) => $state ? 'Success' : 'Pending')
                ->badge()
                ->color(fn ($state) => $state ? 'success' : 'warning'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Order Date'),
        ];
    }
}
