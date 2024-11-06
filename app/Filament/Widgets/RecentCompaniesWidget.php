<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentCompaniesWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Company::query()
                    ->latest()
                    ->withCount('events')
            )
            ->heading(__('filament-widgets.recent-companies.heading'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-widgets.recent-companies.columns.name'))
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('inn')
                    ->label(__('filament-widgets.recent-companies.columns.inn'))
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('events_count')
                    ->label(__('filament-widgets.recent-companies.columns.events'))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-widgets.recent-companies.columns.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('filament-widgets.actions.view'))
                    ->url(fn (Company $record): string => route('filament.admin.resources.companies.edit', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->paginated([5]);
    }
} 