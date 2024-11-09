<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FilterPresetResource\Pages;
use App\Models\FilterPreset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\City;
use App\Enums\EventFormat;
use App\Models\Industry;

class FilterPresetResource extends Resource
{
    protected static ?string $model = FilterPreset::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationGroup = 'settings';

    public static function getModelLabel(): string
    {
        return __('filament-resources.filter-presets.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.filter-presets.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('filament-resources.filter-presets.fields.title'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label(__('filament-resources.filter-presets.fields.slug'))
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Section::make(__('filament-resources.filter-presets.sections.filters'))
                    ->schema([
                        Forms\Components\Select::make('filters.format')
                            ->label(__('filament-resources.events.fields.format'))
                            ->options(EventFormat::getLabels()),

                        Forms\Components\Select::make('filters.city_id')
                            ->label(__('filament-resources.events.fields.city_id'))
                            ->options(City::pluck('title', 'id')),

                        Forms\Components\Select::make('filters.industry_id')
                            ->label(__('filament-resources.events.fields.industry_id'))
                            ->options(Industry::pluck('title', 'id')),
                    ])
                    ->columns(3),

                Forms\Components\Toggle::make('is_active')
                    ->label(__('filament-resources.filter-presets.fields.is_active'))
                    ->default(true),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('filament-resources.filter-presets.fields.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.filter-presets.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('filament-resources.filter-presets.fields.slug'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-resources.filter-presets.fields.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament-resources.filter-presets.fields.sort_order'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-resources.filter-presets.fields.is_active')),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFilterPresets::route('/'),
            'create' => Pages\CreateFilterPreset::route('/create'),
            'edit' => Pages\EditFilterPreset::route('/{record}/edit'),
        ];
    }
}
