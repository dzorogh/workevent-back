<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('filament-resources.users.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.users.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-resources.users.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament-resources.users.fields.name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label(__('filament-resources.users.fields.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->label(__('filament-resources.users.fields.password'))
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                Select::make('role_id')
                    ->label(__('filament-resources.users.fields.role'))
                    ->relationship('role', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Toggle::make('is_active')
                    ->label(__('filament-resources.users.fields.is_active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-resources.users.fields.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('filament-resources.users.fields.email'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role.name')
                    ->label(__('filament-resources.users.fields.role'))
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label(__('filament-resources.users.fields.is_active'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament-resources.users.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('filament-resources.users.fields.role'))
                    ->relationship('role', 'name'),

                TernaryFilter::make('is_active')
                    ->label(__('filament-resources.users.fields.is_active')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (User $record) {
                        if ($record->id === auth()->id()) {
                            Notification::make()
                                ->warning()
                                ->title('Cannot delete yourself')
                                ->body('You cannot delete your own account.')
                                ->send();

                            return false;
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->id === auth()->id()) {
                                    Notification::make()
                                        ->warning()
                                        ->title('Cannot delete yourself')
                                        ->body('You cannot delete your own account.')
                                        ->send();

                                    return false;
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermission('users.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermission('users.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermission('users.delete') ?? false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('users.manage') ?? false;
    }
}
