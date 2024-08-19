<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public static function getModelLabel(): string
    {
        return __('module_names.users.label');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('module_names.users.plural_label');
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
                Tables\Columns\TextColumn::make('email')->label(__('fields.email'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label(__('module_names.roles.plural_label'))
                    ->visible(User::isSuperAdmin() || User::isCompanyAdmin())
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->url(fn(): string => UserResource::getUrl('create')),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make()->url(fn (Model $record): string => LocationResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make()->url(fn(Model $record): string => UserResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
