<?php

namespace App\Filament\Resources;

use App\Models\Key;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Company;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use App\Models\KeyPickUpDropOff;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KeyPickUpDropOffResource\Pages;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use App\Filament\Resources\KeyPickUpDropOffResource\RelationManagers;

class KeyPickUpDropOffResource extends Resource
{
    protected static ?string $model = KeyPickUpDropOff::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $tenantOwnershipRelationshipName = 'company';

    protected static ?string $tenantRelationshipName = 'keyPickUpDropOffs';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.company_management');
    }

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('module_names.key_mgmt.pick_up');
    }

    public static function getPluralModelLabel(): string
    {
        return __('module_names.key_mgmt.menu');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([

                    Forms\Components\Select::make('company_id')->label(__('module_names.companies.label'))
                        ->visible(User::isSuperAdmin())
                        ->live()
                        ->options(Company::pluck('name', 'id'))
                        ->afterStateUpdated(function (Forms\Set $set) {
                            $set('location_id', null);
                            $set('key_id', null);
                            $set('pick_up_user_id', null);
                        }),

                    Forms\Components\Select::make('location_id')->label(__('module_names.locations.label'))
                        ->options(function (?KeyPickUpDropOff $record, Forms\Get $get, Forms\Set $set) {
                            if (User::isSuperAdmin()) {
                                if (! empty($record) && empty($get('company_id'))) {
                                    $set('company_id', $record->key->location->company_id);
                                    $set('location_id', $record->key->location_id);
                                }
                                $company_id = $get('company_id');
                            } else {
                                $company_id = Filament::getTenant()->id;
                            }
                            return Location::where('company_id', $company_id)->pluck('name', 'id');
                        })
                        ->live()
                        ->dehydrated(false),

                    Forms\Components\Select::make('key_id')->label(__('module_names.keys.label'))
                        ->options(function (?KeyPickUpDropOff $record, Forms\Get $get, Forms\Set $set) {
                            if (! empty($record) && empty($get('location_id'))) {
                                $set('location_id', $record->key->location_id);
                                $set('key_id', $record->key_id);
                            }
                            return Key::where('location_id', $get('location_id'))->pluck('name', 'id');
                        })
                        ->required(),

                ])
                    ->disabledOn('edit'),

                Tabs::make('Tabs')->tabs([

                    Tabs\Tab::make('Tab 1')->schema([

                        Forms\Components\DateTimePicker::make('pick_up_time')->label(__('fields.pick_up_time'))
                            ->default(now())
                            ->required(fn(string $operation) => ($operation == 'create') ? true : false)
                            ->readOnly(),

                        Forms\Components\Select::make('pick_up_user_id')->label(__('fields.pick_up_user_id'))
                            ->options(function (?KeyPickUpDropOff $record, Forms\Get $get, Forms\Set $set) {
                                if (User::isSuperAdmin()) {
                                    $company_id = $get('company_id');
                                    if (! empty($record))
                                        return User::where('id', $record->pick_up_user_id)->pluck('name', 'id');
                                    else if ($company_id != null)
                                        return Company::find($company_id)->members()->pluck('name', 'users.id');
                                    else
                                        return [];
                                }
                                return User::where('id', auth()->id())->pluck('name', 'id'); // csakis saját maga látszódik, disabled lesz úgyis
                            })
                            ->live()
                            ->required(fn(string $operation) => ($operation == 'create') ? true : false)
                            ->default(fn() => (User::isSuperAdmin()) ?: auth()->id()) // ->default(auth()->id())
                            ->disabled(!User::isSuperAdmin()),

                        SignaturePad::make('pick_up_sign')->label(__('fields.pick_up_sign'))
                            ->required(fn(string $operation) => ($operation == 'create') ? true : false)
                            ->columnSpanFull(),

                        SignaturePad::make('pick_up_security_sign')->label(__('fields.pick_up_security_sign'))
                            ->required(fn(string $operation) => ($operation == 'create') ? true : false)
                            ->columnSpanFull(),
                    ])
                        ->label(__('module_names.key_mgmt.pick_up'))
                        ->icon('heroicon-o-lock-open')
                        ->disabledOn('edit'),

                    Tabs\Tab::make('Tab 2')->schema([

                        Forms\Components\DateTimePicker::make('drop_off_time')->label(__('fields.drop_off_time'))
                            // ->default(now()) // ez nem működik az Edit oldalon
                            ->afterStateHydrated(function (Forms\Components\DateTimePicker $component, ?string $state) {
                                // ha az érték üres az adatbázisban, állítsunk be egy alapértelmezett értéket, ha nem üres, akkor csak folytassuk a munkát a megadott értékkel
                                // if the value is empty in the database, set a default value, if not, just continue with the default component hydration
                                if (!$state) {
                                    $component->state(now()->toDateTimeString());
                                }
                            })
                            ->required(fn(string $operation) => ($operation == 'edit') ? true : false),

                        Forms\Components\Select::make('drop_off_user_id')->label(__('fields.drop_off_user_id'))
                            ->options(function (?KeyPickUpDropOff $record, Forms\Get $get) {
                                if (User::isSuperAdmin()) {
                                    if (! empty($record)) {
                                        return Company::find($record->key->location->company_id)->members()->pluck('name', 'users.id');
                                    } else {
                                        return [];
                                    }
                                }
                                return User::where('id', auth()->id())->pluck('name', 'id'); // csakis saját maga látszódik, disabled lesz úgyis
                            })
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?string $state) {
                                // ha az érték üres az adatbázisban, állítsunk be egy alapértelmezett értéket, ha nem üres, akkor csak folytassuk a munkát a megadott értékkel
                                // if the value is empty in the database, set a default value, if not, just continue with the default component hydration
                                if (!$state && !User::isSuperAdmin()) {
                                    $component->state(auth()->id());
                                }
                            })
                            ->required(fn(string $operation) => ($operation == 'edit') ? true : false)
                            ->disabled(!User::isSuperAdmin()),

                        SignaturePad::make('drop_off_sign')->label(__('fields.drop_off_sign'))
                            ->required(fn(string $operation) => ($operation == 'edit') ? true : false)
                            ->columnSpanFull(),

                        SignaturePad::make('drop_off_security_sign')->label(__('fields.drop_off_security_sign'))
                            ->required(fn(string $operation) => ($operation == 'edit') ? true : false)
                            ->columnSpanFull(),
                    ])
                        ->label(__('module_names.key_mgmt.drop_off'))
                        ->icon('heroicon-o-lock-closed')
                        ->disabledOn('create'),

                ])
                    ->columnSpanFull()
                    ->activeTab(fn(string $operation) => ($operation == 'create') ? 1 : 2) // ha létre akarok hozni, akkor az 1. lap (kulcsfelvétel) legyen aktív, ha szerkeszteni akarom, akkor a 2. lap (kulcsleadás) legyen aktív
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('id', 'desc')
            ->recordUrl(
                // Alapértelmezett sorra kattintás esemény (amikor még nincs lezárva, akkor szerepkörnek megfelelő szerkesztő útvonal),
                // amikor már lezárásra került, akkor szerepkörnek megfelelő nézet útvonal
                function (Model $record): string {
                    if ($record->drop_off_time == null) { // edit
                        if (User::isSuperAdmin())
                            return route('filament.admin.resources.key-pick-up-drop-offs.edit', ['record' => $record]);
                        else
                            return route('filament.company.resources.key-pick-up-drop-offs.edit', ['tenant' => Filament::getTenant(), 'record' => $record]);
                    } else { // view
                        if (User::isSuperAdmin())
                            return route('filament.admin.resources.key-pick-up-drop-offs.view', ['record' => $record]);
                        else
                            return route('filament.company.resources.key-pick-up-drop-offs.view', ['tenant' => Filament::getTenant(), 'record' => $record]);
                    }
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('key.name')->label(__('module_names.keys.label'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('pick_up_time')->label(__('fields.pick_up_time'))
                    ->searchable()
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('pickUpUser.name')->label(__('fields.pick_up_user_id'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('drop_off_time')->label(__('fields.drop_off_time'))
                    ->searchable()
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('dropOffUser.name')->label(__('fields.drop_off_user_id'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                Tables\Actions\EditAction::make()
                    ->label(__('module_names.key_mgmt.drop_off'))
                    ->color('success')
                    ->visible(fn($record) => $record->drop_off_time == null),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListKeyPickUpDropOffs::route('/'),
            'create' => Pages\CreateKeyPickUpDropOff::route('/create'),
            'view' => Pages\ViewKeyPickUpDropOff::route('/{record}'),
            'edit' => Pages\EditKeyPickUpDropOff::route('/{record}/edit'),
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
