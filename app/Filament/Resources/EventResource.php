<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use App\Models\EventCms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\FilamentGrapesjs\Fields\GrapesJs;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\EventCmsResource\Pages;

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
            ->default(fn () => Auth::id()) // ✅ Ensures the value is set
            ->required(),


            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('status')
                ->options([
                    'Published' => 'Published',
                    'Draft' => 'Draft',
                ])
                ->required(),

                GrapesJs::make('content')
                ->label('Page Builder')
                ->tools([
                    // tools you want to include
                ])
                ->plugins([
                    'grapesjs-tailwind',

                ])
                ->settings([
                    'storageManager' => [
                        'autoload' => false, // ✅ Ensures no old data is auto-loaded
                        'type' => 'local',
                        'options' => [
                            'local' => [
                                'key' => 'gsproject-test' . request('record'), // Make the key unique per event,
                            ],
                        ],
                    ],
                    'styleManager' => [
                        'sectors' => [
                            [
                                'name' => 'General',
                                'open' => false,
                                'buildProps' => [
                                    'background-color',
                                    // other properties you want to include
                                ],
                            ],
                        ],
                    ]
                ])
                ->id( 'page_layout' )
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->toggleable()
                ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => $state === 'Published' ? 'Published' : 'Draft')
                    ->color(fn ($state) => $state === 'Published' ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('staff_id')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalWidth('6xl'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(), // ✅ Bulk delete action added
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventCms::route('/'),
            'create' => Pages\CreateEventCms::route('/create'),
            'edit' => Pages\EditEventCms::route('/{record}/edit'),
        ];
    }
}
