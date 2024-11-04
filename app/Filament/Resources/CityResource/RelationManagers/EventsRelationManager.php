<?php

namespace App\Filament\Resources\CityResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $recordTitleAttribute = 'title';

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

                Tables\Columns\TextColumn::make('mainIndustry.title')
                    ->label('Industry')
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

                Tables\Filters\SelectFilter::make('main_industry_id')
                    ->relationship('mainIndustry', 'title')
                    ->label('Industry')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                // No create/attach actions as events should be managed from the Event resource
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions needed
            ])
            ->defaultSort('start_date', 'desc');
    }
}
