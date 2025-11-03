<?php

namespace App\Filament\Resources\Industries\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Event;
use App\Filament\Resources\Events\EventResource;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Events';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('format')
                    ->badge(),

                TextColumn::make('city.title')
                    ->label('City')
                    ->sortable(),
            ])
            ->recordUrl(fn (Event $record): string => EventResource::getUrl('edit', ['record' => $record]))
            ->filters([
                SelectFilter::make('format')
                    ->options([
                        'forum' => 'Forum',
                        'conference' => 'Conference',
                        'exhibition' => 'Exhibition',
                        'seminar' => 'Seminar',
                        'webinar' => 'Webinar',
                    ]),

                SelectFilter::make('city_id')
                    ->relationship('city', 'title')
                    ->label('City')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                // No create/attach actions as events should be managed from the Event resource
            ])
            ->recordActions([

            ])
            ->toolbarActions([
                // No bulk actions needed
            ])
            ->defaultSort('start_date', 'desc');
    }
}
