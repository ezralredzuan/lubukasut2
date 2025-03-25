<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions\DeleteBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Order Details')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Order Info')
                            ->schema([
                                Forms\Components\TextInput::make('reference_no')
                                    ->label('Reference Number')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->default(fn () => Order::generateReferenceNumber()),
                                Forms\Components\TextInput::make('no_phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('address')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('state')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('postal_code')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('order_date')
                                    ->required(),
                                Forms\Components\Select::make('customer_id')
                                    ->label('Customer')
                                    ->relationship('customer', 'full_name') // Fetches customers by name
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name') // Fetches products by name
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                Forms\Components\Select::make('payment_status')
                                    ->options([
                                        '0' => 'Pending',
                                        '1' => 'Success',
                                    ])
                                    ->default('0')
                                    ->disabled(),
                                Forms\Components\Select::make('delivery_status')
                                    ->options([
                                        '-' => '-',
                                        'Ready To Deliver' => 'Ready To Deliver',
                                        'Delivery In Process' => 'Delivery In Process',
                                        'Delivered' => 'Delivered',
                                        'Received' => 'Received',
                                        'Return' => 'Return',
                                    ])
                                    ->default('-')
                                    ->disabled(),
                            ]),
                        Tab::make('Payment Order')
                            ->schema([
                            Forms\Components\Repeater::make('payments')
                                ->relationship('payments') // Ensure the Order model has a `payments()` relation
                                ->schema([
                                    Forms\Components\DatePicker::make('payment_date')
                                        ->label('Payment Date')
                                        ->required(),

                                    Forms\Components\Select::make('payment_method')
                                        ->options([
                                            'Credit Card' => 'Credit Card',
                                            'Bank Transfer' => 'Bank Transfer',
                                            'PayPal' => 'PayPal',
                                            'Cash' => 'Cash',
                                        ])
                                        ->label('Payment Method')
                                        ->required(),

                                    Forms\Components\TextInput::make('amount')
                                        ->label('Amount')
                                        ->numeric()
                                        ->prefix('$')
                                        ->required(),
                                ])
                                ->columns(4) // Organize inputs in columns
                                ->collapsible(), // Allow users to collapse sections
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('reference_no')->searchable(),
            TextColumn::make('no_phone')->searchable(),
            TextColumn::make('address')->searchable(),
            TextColumn::make('city')->searchable(),
            TextColumn::make('state')->searchable(),
            TextColumn::make('postal_code')->searchable(),
            TextColumn::make('order_date')->date()->sortable(),
            TextColumn::make('payment_status')
                ->formatStateUsing(fn ($state) => $state ? 'Success' : 'Pending')
                ->color(fn ($state) => $state ? 'success' : 'gray'),

            SelectColumn::make('delivery_status')
                ->label('Delivery Status')
                ->options([
                    '0' => '-',
                    '1' => 'Ready To Deliver',
                    '2' => 'Delivery In Process',
                    '3' => 'Delivered',
                    '4' => 'Received',
                    '5' => 'Return',
                ])
                ->default('0')
                ->sortable()
                ->searchable()
                ->afterStateUpdated(fn ($record, $state) => $record->update(['delivery_status' => $state])), // Saves changes automatically


            TextColumn::make('price')->money()->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ]);
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }
}
