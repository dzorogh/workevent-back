<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventTagResource\Pages;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventTagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'events';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('filament-resources.event-tags.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.event-tags.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.event-tags.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Placeholder::make('events_count')
                                    ->label(__('filament-resources.event-tags.fields.events_count'))
                                    ->content(fn (?Tag $record): string =>
                                        $record ? $record->events()->count() : '0'
                                    ),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('filament-resources.timestamps.created_at'))
                                    ->content(fn (?Tag $record): string =>
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
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.event-tags.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.event-tags.fields.events_count'))
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
                Tables\Filters\Filter::make('unused')
                    ->query(fn ($query) => $query->whereDoesntHave('events'))
                    ->label('Unused Tags')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tag $record) {
                        if ($record->events()->count() > 0) {
                            Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('Cannot delete tag')
                                ->body('This tag is being used by events.')
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
                                    Filament\Notifications\Notification::make()
                                        ->warning()
                                        ->title('Cannot delete tags')
                                        ->body('Some selected tags are being used by events.')
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
            EventTagResource\RelationManagers\EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
