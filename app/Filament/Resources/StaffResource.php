<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Account Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->unique()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Hash password before saving
                    ->hiddenOn('edit'), // Hide when editing to avoid overwriting

                // File Upload for Staff Picture
                Forms\Components\FileUpload::make('staff_pic')
                    ->label('Profile Picture')
                    ->image()
                    ->directory('staff_pictures') // Stores images in storage/app/public/staff_pictures
                    ->maxSize(1024) // Max 1MB
                    ->nullable(),

                Forms\Components\TextInput::make('staff_name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('no_phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique()
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('hired_date')
                    ->required(),

                // Role selection from Spatie Roles
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options(Role::pluck('name', 'name')) // Get roles from Spatie
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('staff_pic')->circular(), // Display profile picture
                Tables\Columns\TextColumn::make('username')->searchable(),
                Tables\Columns\TextColumn::make('staff_name')->searchable(),
                Tables\Columns\TextColumn::make('no_phone')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('hired_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('role')->searchable(),

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
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Staff $record) => $record->delete()), // Deleting the record
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
            'index' => Pages\ManageStaff::route('/'),
        ];
    }
}
