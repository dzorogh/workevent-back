<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Enums\ParticipationType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-resources.companies.relations.events.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.companies.relations.events.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('filament-resources.companies.relations.events.fields.start_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('format')
                    ->label(__('filament-resources.companies.relations.events.fields.format'))
                    ->badge(),

                Tables\Columns\TextColumn::make('pivot.participation_type')
                    ->label(__('filament-resources.companies.relations.events.fields.participation_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ParticipationType::from($state)->getLabel())
                    ->color(fn (string $state) => ParticipationType::from($state)->getColor()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('format')
                    ->label(__('filament-resources.companies.relations.events.fields.format')),

                Tables\Filters\SelectFilter::make('participation_type')
                    ->options(ParticipationType::getLabels())
                    ->label(__('filament-resources.companies.relations.events.fields.participation_type')),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('participation_type')
                            ->label(__('filament-resources.companies.relations.events.fields.participation_type'))
                            ->options(ParticipationType::getLabels())
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
