<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\RelationManagers\EventsRelationManager;
use App\Filament\Resources\CityResource\Pages;
use Filament\Notifications\Notification;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'references';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('filament-resources.cities.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.cities.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.cities.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Placeholder::make('events_count')
                                    ->label(__('filament-resources.cities.fields.events_count'))
                                    ->content(fn (?City $record): string =>
                                        $record ? $record->events()->count() : '0'
                                    ),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('filament::resources.timestamps.created_at'))
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
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.cities.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.cities.fields.events_count'))
                    ->sortable(),

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
                Tables\Filters\Filter::make('has_events')
                    ->label(__('filament-resources.cities.filters.has_events'))
                    ->query(fn ($query) => $query->has('events'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_events')
                    ->label(__('filament-resources.cities.filters.no_events'))
                    ->query(fn ($query) => $query->doesntHave('events'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
