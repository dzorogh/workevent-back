<?php

namespace App\Filament\Resources\Cities;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Filament\Resources\Cities\Pages\CreateCity;
use App\Filament\Resources\Cities\Pages\EditCity;
use App\Filament\Resources\Cities\RelationManagers\EventsRelationManager;
use App\Filament\Resources\CityResource\Pages;
use Filament\Notifications\Notification;
use App\Models\City;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';

    protected static string | \UnitEnum | null $navigationGroup = 'references';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('filament-resources.cities.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.cities.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament-resources.cities.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true),

                        Grid::make()
                            ->schema([
                                Placeholder::make('events_count')
                                    ->label(__('filament-resources.cities.fields.events_count'))
                                    ->content(fn (?City $record): string =>
                                        $record ? $record->events()->count() : '0'
                                    ),

                                Placeholder::make('created_at')
                                    ->label(__('filament-resources.timestamps.created_at'))
                                    ->content(fn (?City $record): string =>
                                        $record ? $record->created_at->diffForHumans() : '-'
                                    ),
                            ])
                            ->columns(2)
                            ->visibleOn('edit'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-resources.cities.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.cities.fields.events_count'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('has_events')
                    ->label(__('filament-resources.cities.filters.has_events'))
                    ->query(fn ($query) => $query->has('events'))
                    ->toggle(),

                Filter::make('no_events')
                    ->label(__('filament-resources.cities.filters.no_events'))
                    ->query(fn ($query) => $query->doesntHave('events'))
                    ->toggle(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (City $record) {
                        if ($record->events()->count() > 0) {
                            Notification::make()
                                ->warning()
                                ->title('Cannot delete city')
                                ->body('This city has associated events.')
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
                                if ($record->events()->count() > 0) {
                                    Notification::make()
                                        ->warning()
                                        ->title('Cannot delete cities')
                                        ->body('Some selected cities have associated events.')
                                        ->send();

                                    return false;
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'edit' => EditCity::route('/{record}/edit'),
        ];
    }
}
