<?php

namespace App\Filament\Resources\IndustryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Event;
use App\Filament\Resources\EventResource;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Events';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('format')
                    ->badge(),

                Tables\Columns\TextColumn::make('city.title')
                    ->label('City')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('format')
                    ->options([
                        'forum' => 'Forum',
                        'conference' => 'Conference',
                        'exhibition' => 'Exhibition',
                        'seminar' => 'Seminar',
                        'webinar' => 'Webinar',
                    ]),

                Tables\Filters\SelectFilter::make('city_id')
                    ->relationship('city', 'title')
                    ->label('City')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                // No create/attach actions as events should be managed from the Event resource
            ])
            ->actions([

            ])
            ->bulkActions([
                // No bulk actions needed
            ])
            ->defaultSort('start_date', 'desc');
    }
}
