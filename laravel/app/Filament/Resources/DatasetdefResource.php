<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatasetdefResource\Pages;
use App\Filament\Resources\DatasetdefResource\RelationManagers;
use App\Models\Datasetdef;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DatasetdefResource extends Resource
{
    protected static ?string $model = Datasetdef::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
	
		protected static ?string $navigationGroup = 'Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('database_id')
                    ->required() 
                    ->numeric(),
                Forms\Components\TextInput::make('datafiletype_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('database.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('datafiletype.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DatafiletypesRelationManager::class,
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatasetdefs::route('/'),
            'create' => Pages\CreateDatasetdef::route('/create'),
            'edit' => Pages\EditDatasetdef::route('/{record}/edit'),
        ];
    }
}
