<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Filament\Resources\PromotionResource\RelationManagers;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('staff_id')
                ->default(fn () => Staff::where('id', Auth::id())->value('id')),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('promotion_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('discount_percentage')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('valid_until_date')
                    ->required(),
                Forms\Components\TextInput::make('staff_id')
                    ->hidden(), // Hide staff_id if not needed
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('promotion_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_until_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('staff_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Promotion $record) => $record->delete()), // Deleting the record
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
            'index' => Pages\ManagePromotions::route('/'),
        ];
    }
}
