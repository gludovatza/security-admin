<?php

namespace App\Filament\Resources\LocationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\KeyResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class KeysRelationManager extends RelationManager
{
    protected static string $relationship = 'keys';

    public static function getModelLabel(): string
    {
        return __('module_names.keys.label');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('module_names.keys.plural_label');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // elemek szerkesztése az akciókon keresztül
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('fields.name'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->url(fn(): string => KeyResource::getUrl('create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn(Model $record): string => KeyResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
