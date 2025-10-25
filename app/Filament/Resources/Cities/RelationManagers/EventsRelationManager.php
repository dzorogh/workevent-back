<?php

namespace App\Filament\Resources\Cities\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
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
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('format')
                    ->badge(),

                TextColumn::make('industry.title')
                    ->label('Industry')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('format')
                    ->options([
                        'forum' => 'Forum',
                        'conference' => 'Conference',
                        'exhibition' => 'Exhibition',
                        'seminar' => 'Seminar',
                        'webinar' => 'Webinar',
                    ]),

                SelectFilter::make('industry_id')
                    ->relationship('industry', 'title')
                    ->label('Industry')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                // No create/attach actions as events should be managed from the Event resource
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                // No bulk actions needed
            ])
            ->defaultSort('start_date', 'desc');
    }
}
