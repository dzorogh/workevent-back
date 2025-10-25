<?php

namespace App\Filament\Resources\Industries;

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
use App\Filament\Resources\Industries\RelationManagers\EventsRelationManager;
use App\Filament\Resources\Industries\Pages\ListIndustries;
use App\Filament\Resources\Industries\Pages\CreateIndustry;
use App\Filament\Resources\Industries\Pages\EditIndustry;
use App\Filament\Resources\IndustryResource\Pages;
use App\Models\Industry;
use Exception;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class IndustryResource extends Resource
{
    protected static ?string $model = Industry::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-library';

    protected static string | \UnitEnum | null $navigationGroup = 'references';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('filament-resources.industries.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.industries.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament-resources.industries.fields.title'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->label(__('filament-resources.industries.fields.slug'))
                            ->required()
                            ->maxLength(255),

                        Grid::make()
                            ->schema([
                                Placeholder::make('events_count')
                                    ->label(__('filament-resources.industries.fields.events_count'))
                                    ->content(
                                        fn(?Industry $record): string => $record ? $record->events()->count() : '0'
                                    ),

                                Placeholder::make('created_at')
                                    ->label(__('filament-resources.timestamps.created_at'))
                                    ->content(
                                        fn(?Industry $record): string => $record ? $record->created_at->diffForHumans() : '-'
                                    ),
                            ])
                            ->columns(2)
                            ->visibleOn('edit'),
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-resources.industries.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.industries.fields.events_count'))
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
                    ->label(__('filament-resources.industries.filters.has_events'))
                    ->query(fn($query) => $query->has('events'))
                    ->toggle(),

                Filter::make('no_events')
                    ->label(__('filament-resources.industries.filters.no_events'))
                    ->query(fn($query) => $query->doesntHave('events'))
                    ->toggle(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (Industry $record) {
                        if ($record->events()->count() > 0) {
                            Notification::make()
                                ->warning()
                                ->title('Cannot delete industry')
                                ->body('This industry has associated events.')
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
                                        ->title('Cannot delete industries')
                                        ->body('Some selected industries have associated events.')
                                        ->send();

                                    return false;
                                }
                            }
                        }),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
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
            'index' => ListIndustries::route('/'),
            'create' => CreateIndustry::route('/create'),
            'edit' => EditIndustry::route('/{record}/edit'),
        ];
    }
}
