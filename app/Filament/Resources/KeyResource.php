<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeyResource\Pages;
use App\Filament\Resources\KeyResource\RelationManagers;
use App\Models\Company;
use App\Models\Key;
use App\Models\Location;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class KeyResource extends Resource
{
    protected static ?string $model = Key::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $tenantOwnershipRelationshipName = 'company';

    protected static ?string $tenantRelationshipName = 'keys';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.company_management');
    }

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('module_names.keys.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('module_names.keys.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('fields.name'))
                    ->rules(fn(Forms\Get $get) => Rule::unique('keys', 'name')->where('location_id', $get('location_id')))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('company_id')->label(__('module_names.companies.label'))
                    ->visible(User::isSuperAdmin())
                    ->live()
                    ->dehydrated(false)
                    ->options(Company::pluck('name', 'id')),
                Forms\Components\Select::make('location_id')->label(__('module_names.locations.label'))
                    ->options(function (?Key $record, Forms\Get $get, Forms\Set $set) {
                        if( User::isSuperAdmin() )
                        {
                            if (! empty($record) && empty($get('company_id'))) {
                                $set('company_id', $record->location->company_id);
                                $set('location_id', $record->location_id);
                            }
                            $company_id = $get('company_id');
                        } else {
                            $company_id = Filament::getTenant()->id;
                        }
                        return Location::where('company_id', $company_id)->pluck('name', 'id');
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')->label(__('module_names.companies.plural_label'))
                    ->visible(User::isSuperAdmin())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('location.name')->label(__('module_names.locations.label'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListKeys::route('/'),
            'create' => Pages\CreateKey::route('/create'),
            'edit' => Pages\EditKey::route('/{record}/edit'),
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
