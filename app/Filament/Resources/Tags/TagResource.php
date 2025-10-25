<?php

namespace App\Filament\Resources\Tags;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Filament\Notifications\Notification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Tags\RelationManagers\EventsRelationManager;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\TagResource\Pages;
use App\Models\Tag;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static string | \UnitEnum | null $navigationGroup = 'references';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('filament-resources.event-tags.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.event-tags.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament-resources.event-tags.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true),

                        Grid::make()
                            ->schema([
                                Placeholder::make('events_count')
                                    ->label(__('filament-resources.event-tags.fields.events_count'))
                                    ->content(fn (?Tag $record): string =>
                                        $record ? $record->events()->count() : '0'
                                    ),

                                Placeholder::make('created_at')
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
                TextColumn::make('title')
                    ->label(__('filament-resources.event-tags.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.event-tags.fields.events_count'))
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
                Filter::make('unused')
                    ->query(fn ($query) => $query->whereDoesntHave('events'))
                    ->label('Unused Tags')
                    ->toggle(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (Tag $record) {
                        if ($record->events()->count() > 0) {
                            Notification::make()
                                ->warning()
                                ->title('Cannot delete tag')
                                ->body('This tag is being used by events.')
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
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }
}
