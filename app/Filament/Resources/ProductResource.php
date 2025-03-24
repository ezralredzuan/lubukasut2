<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Staff;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventory Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Hidden::make('staff_id')
                            ->default(fn () => Staff::where('id', Auth::id())->value('id')),

                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('brand_id')
                            ->label('Brand')
                            ->options(\App\Models\Brand::pluck('brand_name', 'id')) // Fetch brand names with their IDs
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Unisex' => 'Unisex',
                            ])
                            ->required()
                            ->columnSpanFull(),

                            Forms\Components\Fieldset::make('Size')
                            ->schema([
                                Forms\Components\Radio::make('size')
                                    ->options([
                                        '3' => '3', '3.5' => '3.5', '4' => '4', '4.5' => '4.5',
                                        '5' => '5', '5.5' => '5.5', '6' => '6', '6.5' => '6.5',
                                        '7' => '7', '7.5' => '7.5', '8' => '8', '8.5' => '8.5',
                                        '9' => '9', '9.5' => '9.5', '10' => '10', '10.5' => '10.5',
                                        '11' => '11', '11.5' => '11.5', '12' => '12',
                                    ])

                                    ->columns(6) // Arrange in a 6-column grid
                                    ->required()
                                    ->inline() // Show all options in a single row
                                    ->extraAttributes(['class' => 'custom-radio']),
                            ])
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('product_image')
                            ->label('Picture')
                            ->image()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('staff_id')
                            ->hidden(), // Hide staff_id if not needed
                    ])
                    ->columns(2), // Structured layout
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product_image'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('brand_id')->sortable(),
                Tables\Columns\TextColumn::make('gender')->sortable(),
                Tables\Columns\TextColumn::make('size')->sortable(),
                Tables\Columns\TextColumn::make('price')->money()->sortable(),
                Tables\Columns\TextColumn::make('staff_id')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Product $record) => $record->delete()), // Deleting the record
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
