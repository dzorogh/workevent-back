<?php

namespace App\Filament\Resources\Speakers;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Speakers\RelationManagers\TopicsRelationManager;
use App\Filament\Resources\Speakers\RelationManagers\EventsRelationManager;
use App\Filament\Resources\Speakers\Pages\ListSpeakers;
use App\Filament\Resources\Speakers\Pages\CreateSpeaker;
use App\Filament\Resources\Speakers\Pages\EditSpeaker;
use App\Filament\Resources\SpeakerResource\Pages;
use App\Models\Speaker;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | \UnitEnum | null $navigationGroup = 'participants';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('filament-resources.speakers.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.speakers.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament-resources.speakers.sections.personal_info'))
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('filament-resources.speakers.fields.first_name'))
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('middle_name')
                            ->label(__('filament-resources.speakers.fields.middle_name'))
                            ->maxLength(255),
                            
                        TextInput::make('last_name')
                            ->label(__('filament-resources.speakers.fields.last_name'))
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('email')
                            ->label(__('filament-resources.speakers.fields.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),

                Section::make(__('filament-resources.speakers.sections.topics'))
                    ->schema([
                        Repeater::make('topics')
                            ->relationship()
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('filament-resources.speakers.fields.topic_title'))
                                    ->required()
                                    ->maxLength(255),
                                    
                                Textarea::make('description')
                                    ->label(__('filament-resources.speakers.fields.topic_description'))
                                    ->rows(3)
                                    ->maxLength(65535),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->addActionLabel(__('filament-resources.speakers.actions.add_topic'))
                            ->collapsible(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label(__('filament-resources.speakers.fields.full_name'))
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable()
                    ->formatStateUsing(fn (Speaker $record): string => 
                        trim("{$record->first_name} {$record->middle_name} {$record->last_name}")
                    ),
                    
                TextColumn::make('email')
                    ->label(__('filament-resources.speakers.fields.email'))
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('topics_count')
                    ->counts('topics')
                    ->label(__('filament-resources.speakers.fields.topics_count')),
                    
                TextColumn::make('events_count')
                    ->counts('events')
                    ->label('Events'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('has_topics')
                    ->query(fn ($query) => $query->has('topics'))
                    ->label('With Topics')
                    ->toggle(),

                Filter::make('has_events')
                    ->query(fn ($query) => $query->has('events'))
                    ->label('With Events')
                    ->toggle(),
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
            TopicsRelationManager::class,
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpeakers::route('/'),
            'create' => CreateSpeaker::route('/create'),
//            'view' => Pages\ViewSpeaker::route('/{record}'),
            'edit' => EditSpeaker::route('/{record}/edit'),
        ];
    }
}
