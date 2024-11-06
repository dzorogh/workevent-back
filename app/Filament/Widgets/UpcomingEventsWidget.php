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
            ->heading(__('filament-widgets.upcoming-events.heading'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-widgets.upcoming-events.columns.title'))
                    ->searchable()
                    ->limit(50),
                    
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('filament-widgets.upcoming-events.columns.start_date'))
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('format')
                    ->label(__('filament-widgets.upcoming-events.columns.format'))
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('city.title')
                    ->label(__('filament-widgets.upcoming-events.columns.city')),
                    
                Tables\Columns\TextColumn::make('mainIndustry.title')
                    ->label(__('filament-widgets.upcoming-events.columns.industry')),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('filament-widgets.actions.view'))
                    ->url(fn (Event $record): string => route('filament.admin.resources.events.edit', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->paginated([5]);
    }
} 