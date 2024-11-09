<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndustryResource\Pages;
use App\Models\Industry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class IndustryResource extends Resource
{
    protected static ?string $model = Industry::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'references';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('filament-resources.industries.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.industries.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.industries.fields.title'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Placeholder::make('main_events_count')
                                    ->label(__('filament-resources.industries.fields.main_events_count'))
                                    ->content(fn(?Industry $record): string => $record ? $record->mainEvents()->count() : '0'
                                    ),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('filament-resources.timestamps.created_at'))
                                    ->content(fn(?Industry $record): string => $record ? $record->created_at->diffForHumans() : '-'
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
                    ->label(__('filament-resources.industries.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('main_events_count')
                    ->counts('mainEvents')
                    ->label(__('filament-resources.industries.fields.main_events_count'))
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
                    ->label(__('filament-resources.industries.filters.has_events'))
                    ->query(fn($query) => $query->has('mainEvents'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_events')
                    ->label(__('filament-resources.industries.filters.no_events'))
                    ->query(fn($query) => $query->doesntHave('mainEvents'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Industry $record) {
                        if ($record->mainEvents()->count() > 0) {
                            Notification::make()
                                ->warning()
                                ->title('Cannot delete industry')
                                ->body('This industry has associated events.')
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
                                if ($record->mainEvents()->count() > 0) {
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            IndustryResource\RelationManagers\EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIndustries::route('/'),
            'create' => Pages\CreateIndustry::route('/create'),
            'edit' => Pages\EditIndustry::route('/{record}/edit'),
        ];
    }
}
