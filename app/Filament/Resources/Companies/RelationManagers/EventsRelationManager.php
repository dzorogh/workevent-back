<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use App\Enums\ParticipationType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
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
                TextColumn::make('title')
                    ->label(__('filament-resources.companies.relations.events.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label(__('filament-resources.companies.relations.events.fields.start_date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('format')
                    ->label(__('filament-resources.companies.relations.events.fields.format'))
                    ->badge(),

                TextColumn::make('pivot.participation_type')
                    ->label(__('filament-resources.companies.relations.events.fields.participation_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ParticipationType::from($state)->getLabel())
                    ->color(fn (string $state) => ParticipationType::from($state)->getColor()),
            ])
            ->filters([
                SelectFilter::make('format')
                    ->label(__('filament-resources.companies.relations.events.fields.format')),

                SelectFilter::make('participation_type')
                    ->options(ParticipationType::getLabels())
                    ->label(__('filament-resources.companies.relations.events.fields.participation_type')),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('participation_type')
                            ->label(__('filament-resources.companies.relations.events.fields.participation_type'))
                            ->options(ParticipationType::getLabels())
                            ->required(),
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
