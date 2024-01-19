<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatafileResource\Pages;
use App\Filament\Resources\DatafileResource\RelationManagers;
use App\Models\Datafile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DatafileResource extends Resource
{
    protected static ?string $model = Datafile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dataset_id')
                    ->relationship('dataset', 'name')
                    ->required(),
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
                Tables\Columns\TextColumn::make('dataset.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('datafiletype_id')
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
                Tables\Actions\ViewAction::make(),
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
					//
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatafiles::route('/'),
            'create' => Pages\CreateDatafile::route('/create'),
            'view' => Pages\ViewDatafile::route('/{record}'),
            'edit' => Pages\EditDatafile::route('/{record}/edit'),
        ];
    }
}
