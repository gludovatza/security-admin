<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LocationResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'companyLocations';

    public static function getModelLabel(): string
    {
        return __('module_names.locations.label');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('module_names.locations.plural_label');
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
            ->striped()
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('fields.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')->label(__('fields.address'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->url(fn(): string => LocationResource::getUrl('create')),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make()->url(fn (Model $record): string => LocationResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make()->url(fn(Model $record): string => LocationResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
