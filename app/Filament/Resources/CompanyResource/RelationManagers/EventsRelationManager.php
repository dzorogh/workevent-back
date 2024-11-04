<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_id')
                    ->relationship('event', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('participation_type')
                    ->options([
                        'organizer' => 'Organizer',
                        'sponsor' => 'Sponsor',
                        'participant' => 'Participant',
                        'partner' => 'Partner',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('format')
                    ->badge(),

                Tables\Columns\TextColumn::make('pivot.participation_type')
                    ->badge()
                    ->colors([
                        'warning' => 'sponsor',
                        'success' => 'organizer',
                        'primary' => 'participant',
                        'info' => 'partner',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('format'),
                Tables\Filters\SelectFilter::make('participation_type')
                    ->options([
                        'organizer' => 'Organizer',
                        'sponsor' => 'Sponsor',
                        'participant' => 'Participant',
                        'partner' => 'Partner',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('participation_type')
                            ->options([
                                'organizer' => 'Organizer',
                                'sponsor' => 'Sponsor',
                                'participant' => 'Participant',
                                'partner' => 'Partner',
                            ])
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
