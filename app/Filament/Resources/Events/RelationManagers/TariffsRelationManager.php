<?php

namespace App\Filament\Resources\Events\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('filament-resources.events.relations.tariffs.fields.title'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('price')
                    ->label(__('filament-resources.events.relations.tariffs.fields.price'))
                    ->numeric()
                    ->prefix('₽')
                    ->required()
                    ->step(0.01),

                MarkdownEditor::make('description')
                    ->label(__('filament-resources.events.relations.tariffs.fields.description'))
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label(__('filament-resources.events.relations.tariffs.fields.is_active'))
                    ->default(true),

                TextInput::make('sort_order')
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
                TextColumn::make('title')
                    ->label(__('filament-resources.events.relations.tariffs.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label(__('filament-resources.events.relations.tariffs.fields.price'))
                    ->money('RUB')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label(__('filament-resources.events.relations.tariffs.fields.is_active'))
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label(__('filament-resources.events.relations.tariffs.fields.sort_order'))
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('filament-resources.events.relations.tariffs.fields.is_active')),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
} 