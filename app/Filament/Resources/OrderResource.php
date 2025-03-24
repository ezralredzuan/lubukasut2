<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('reference_no')
                ->label('Reference Number')
                ->disabled() // Make it read-only
                ->dehydrated(false) // Prevent Filament from overriding the model's auto-generated value
                ->default(fn () => \App\Models\Order::generateReferenceNumber()),

                Forms\Components\TextInput::make('no_phone') // Fixed name from no_phone to no_phone
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

                Forms\Components\TextInput::make('payment_status')
                    ->required()
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('delivery_status')
                    ->required()
                    ->numeric()
                    ->default(0),

                Forms\Components\DatePicker::make('shipped_date'),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),

                // Fetch customer_id from Customers table but hide it in form
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'full_name'), // Assumes 'name' is the column representing customer names

                // Fetch product_id from Products table but hide it in form
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name'), // Assumes 'name' is the column representing product names
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_no')->searchable(),
                Tables\Columns\TextColumn::make('no_phone')->searchable(),
                Tables\Columns\TextColumn::make('address')->searchable(),
                Tables\Columns\TextColumn::make('city')->searchable(),
                Tables\Columns\TextColumn::make('state')->searchable(),
                Tables\Columns\TextColumn::make('postal_code')->searchable(),
                Tables\Columns\TextColumn::make('order_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('delivery_status')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('shipped_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('price')->money()->sortable(),

                // Show customer name instead of customer_id
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                // Show product name instead of product_id
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }
}
