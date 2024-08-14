<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $tenantOwnershipRelationshipName = 'companies';

    protected static ?string $tenantRelationshipName = 'members';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.user_management');
    }

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('module_names.users.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('module_names.users.plural_label');
    }

    public static function form(Form $form): Form
    {
        $isAdmin = auth()->user()->hasRole(Utils::getSuperAdminName());
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')->label(__('fields.name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')->label(__('fields.email'))
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->maxLength(255)
                        ->required(static fn(Page $livewire): string => $livewire instanceof CreateUser,)
                        ->dehydrateStateUsing(
                            fn(?string $state): ?string =>
                            filled($state) ? Hash::make($state) : null
                        )
                        ->dehydrated(
                            fn(?string $state): bool =>
                            filled($state)
                        )
                        ->label(
                            fn(Page $livewire): string => ($livewire instanceof EditUser) ? __('fields.new_password') : __('fields.password')
                        ),
                    Forms\Components\Select::make('roles')->label(__('module_names.roles.label'))
                        ->visible($isAdmin)
                        ->required($isAdmin)
                        ->relationship('roles', 'name')
                        ->columnSpanFull()
                        ->multiple()
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('companies')->label(__('module_names.companies.label'))
                        ->visible($isAdmin)
                        ->required($isAdmin)
                        ->relationship('companies', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                ])
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
                Tables\Columns\TextColumn::make('email')->label(__('fields.email'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('companies.name')->label(__('module_names.companies.plural_label'))
                    ->visible($isAdmin)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label(__('module_names.roles.plural_label'))
                    ->visible($isAdmin)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('fields.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->label(__('fields.deleted_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->searchable()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function isAdmin(): bool {}
}
