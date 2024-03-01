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
//use Illuminate\Database\Eloquent\Model; //jw:note used for "injecting the current form record" https://filamentphp.com/docs/3.x/forms/advanced#injecting-the-current-form-record
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\FileUpload; 

class DatafileResource extends Resource
{
    protected static ?string $model = Datafile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

		protected static ?string $navigationGroup = 'Data';
		
		protected static ?int $navigationSort = 4;
		
		public static function form(Form $form): Form
    {
        //print_r($form->record);
        //print_r($this->getOwnerRecord());
/* https://laraveldaily.com/post/filament-relation-manager-live-update-main-form-after-changes */
        return $form
            ->schema([
                Forms\Components\Select::make('dataset_id')
                    ->relationship('dataset', 'name')
                    ->required(),
                Forms\Components\Select::make('datafiletype_id')
                    ->relationship('datafiletype', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                FileUpload::make('attachment')
                    ->disk('public')
                    ->moveFiles()
                    ->directory(function (?Datafile $record) {
                        return $record->path();
                        return $record->dataset->database()->get()->value('id')."/".$record->dataset()->get()->value('id')."/".$record->id;
                        return $record->dataset->database()->get()->value('id');
                        return $record->dataset()->get()->value('id');
                        return $record->id;
                    }) 
                    /*
                    ->directory(function (RelationManager $livewire): int {
                        return $livewire->getOwnerRecords()->stores()
                          ->value('id');
                    })*/
                    /*->directory(Datafile::where('id', Request::segments()[1])->get()->value('id'))*/
/*                    ->directory(Database::where('id', 1)->get()->value('id')."/".Dataset::where('id',))*/
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dataset.name')
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
