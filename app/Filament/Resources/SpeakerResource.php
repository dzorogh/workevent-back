<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Models\Speaker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'people';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('filament-resources.speakers.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.speakers.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament-resources.speakers.sections.personal_info'))
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label(__('filament-resources.speakers.fields.first_name'))
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('middle_name')
                            ->label(__('filament-resources.speakers.fields.middle_name'))
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('last_name')
                            ->label(__('filament-resources.speakers.fields.last_name'))
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament-resources.speakers.fields.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament-resources.speakers.sections.topics'))
                    ->schema([
                        Forms\Components\Repeater::make('topics')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament-resources.speakers.fields.topic_title'))
                                    ->required()
                                    ->maxLength(255),
                                    
                                Forms\Components\Textarea::make('description')
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
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('filament-resources.speakers.fields.full_name'))
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable()
                    ->formatStateUsing(fn (Speaker $record): string => 
                        trim("{$record->first_name} {$record->middle_name} {$record->last_name}")
                    ),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-resources.speakers.fields.email'))
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('topics_count')
                    ->counts('topics')
                    ->label(__('filament-resources.speakers.fields.topics_count')),
                    
                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->label('Events'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_topics')
                    ->query(fn ($query) => $query->has('topics'))
                    ->label('With Topics')
                    ->toggle(),

                Tables\Filters\Filter::make('has_events')
                    ->query(fn ($query) => $query->has('events'))
                    ->label('With Events')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SpeakerResource\RelationManagers\TopicsRelationManager::class,
            SpeakerResource\RelationManagers\EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
//            'view' => Pages\ViewSpeaker::route('/{record}'),
            'edit' => Pages\EditSpeaker::route('/{record}/edit'),
        ];
    }
}
