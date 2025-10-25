<?php

namespace App\Filament\Resources\Series;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Series\Pages\ListSeries;
use App\Filament\Resources\Series\Pages\CreateSeries;
use App\Filament\Resources\Series\Pages\EditSeries;
use App\Filament\Resources\SeriesResource\Pages;
use App\Filament\Resources\Series\RelationManagers\EventsRelationManager;
use App\Models\Series;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeriesResource extends Resource
{
    protected static ?string $model = Series::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'events';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('filament-resources.event-series.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.event-series.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament-resources.event-series.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Placeholder::make('created_at')
                            ->label(__('filament-resources.timestamps.created_at'))
                            ->content(fn (?Series $record): string =>
                            $record ? $record->created_at->diffForHumans() : '-'
                            ),

                        Placeholder::make('updated_at')
                            ->label(__('filament-resources.timestamps.updated_at'))
                            ->content(fn (?Series $record): string =>
                            $record ? $record->updated_at->diffForHumans() : '-'
                            ),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-resources.event-series.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.event-series.fields.events_count'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament-resources.timestamps.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('filament-resources.timestamps.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListSeries::route('/'),
            'create' => CreateSeries::route('/create'),
            'edit' => EditSeries::route('/{record}/edit'),
        ];
    }
}
