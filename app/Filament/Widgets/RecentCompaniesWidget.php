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
            ->heading('Recently Added Companies')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Company Name')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('inn')
                    ->label('INN')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('events_count')
                    ->label('Events')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Company $record): string => route('filament.admin.resources.companies.edit', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->paginated([5]);
    }
} 