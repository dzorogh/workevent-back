<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
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
                TextColumn::make('title')
                    ->label(__('filament-widgets.recent-companies.columns.name'))
                    ->searchable(),
                    
                TextColumn::make('inn')
                    ->label(__('filament-widgets.recent-companies.columns.inn'))
                    ->searchable(),
                    
                TextColumn::make('events_count')
                    ->label(__('filament-widgets.recent-companies.columns.events'))
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label(__('filament-widgets.recent-companies.columns.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(__('filament-widgets.actions.view'))
                    ->url(fn (Company $record): string => route('filament.admin.resources.companies.edit', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->paginated([5]);
    }
} 