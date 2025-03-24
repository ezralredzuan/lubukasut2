<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Dotswan\FilamentGrapesjs\Forms\Grapesjs;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Website Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('staff_id')
                    ->default(fn () => Staff::where('id', Auth::id())->value('id')),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->options([
                        'Published' => 'Published',
                        'Draft' => 'Draft',
                    ])
                    ->required(),

                    Grapesjs::make('content')
                    ->label('Page Builder')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => $state === 'Published' ? 'Published' : 'Draft')
                    ->color(fn ($state) => $state === 'Published' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEvents::route('/'),
        ];
    }
}
