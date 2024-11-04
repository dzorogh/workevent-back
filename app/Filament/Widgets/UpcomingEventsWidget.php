<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingEventsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date')
            )
            ->heading('Upcoming Events')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                    
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('format')
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('city.title')
                    ->label('City'),
                    
                Tables\Columns\TextColumn::make('mainIndustry.title')
                    ->label('Industry'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Event $record): string => route('filament.admin.resources.events.edit', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->paginated([5]);
    }
} 