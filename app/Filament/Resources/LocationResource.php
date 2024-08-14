<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Support\Utils;
use App\Filament\Resources\LocationResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LocationResource\RelationManagers;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $tenantOwnershipRelationshipName = 'company';

    protected static ?string $tenantRelationshipName = 'companyLocations';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.company_management');
    }

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('module_names.locations.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('module_names.locations.plural_label');
    }

    public static function form(Form $form): Form
    {
        $isAdmin = auth()->user()->hasRole(Utils::getSuperAdminName());
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')->label(__('fields.address'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('company_id')->label(__('module_names.companies.label'))
                    ->visible($isAdmin)
                    ->required($isAdmin)
                    ->relationship('company', 'name')
                    ->preload()
                    ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        $isAdmin = auth()->user()->hasRole(Utils::getSuperAdminName());
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('fields.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')->label(__('fields.address'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')->label(__('module_names.companies.plural_label'))
                    ->visible($isAdmin)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('fields.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label(__('fields.updated_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->label(__('fields.deleted_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
