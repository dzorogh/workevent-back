<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TariffsRelationManager extends RelationManager
{
    protected static string $relationship = 'tariffs';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-resources.events.relations.tariffs.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('filament-resources.events.relations.tariffs.fields.title'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('price')
                    ->label(__('filament-resources.events.relations.tariffs.fields.price'))
                    ->numeric()
                    ->prefix('₽')
                    ->required()
                    ->step(0.01),

                Forms\Components\MarkdownEditor::make('description')
                    ->label(__('filament-resources.events.relations.tariffs.fields.description'))
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label(__('filament-resources.events.relations.tariffs.fields.is_active'))
                    ->default(true),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('filament-resources.events.relations.tariffs.fields.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.events.relations.tariffs.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('filament-resources.events.relations.tariffs.fields.price'))
                    ->money('RUB')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-resources.events.relations.tariffs.fields.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament-resources.events.relations.tariffs.fields.sort_order'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-resources.events.relations.tariffs.fields.is_active')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
} 