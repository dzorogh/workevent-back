<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\EventSeries;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function getModelLabel(): string
    {
        return __('filament-resources.events.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.events.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament-resources.events.sections.basic_info'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.events.fields.title'))
                            ->required()
                            ->maxLength(255),

                        Select::make('series_id')
                            ->label(__('filament-resources.events.fields.series_id'))
                            ->relationship('series', 'title')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament-resources.event-series.fields.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(EventSeries::class),
                            ])
                            ->createOptionModalHeading(__('filament-resources.event-series.actions.create.heading')),

                        Forms\Components\TextInput::make('website')
                            ->label(__('filament-resources.events.fields.website'))
                            ->url()
                            ->prefix('https://')
                            ->maxLength(255),

                        Forms\Components\Select::make('format')
                            ->label(__('filament-resources.events.fields.format'))
                            ->options([
                                'forum' => __('filament-resources.events.formats.forum'),
                                'conference' => __('filament-resources.events.formats.conference'),
                                'exhibition' => __('filament-resources.events.formats.exhibition'),
                                'seminar' => __('filament-resources.events.formats.seminar'),
                                'webinar' => __('filament-resources.events.formats.webinar'),
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'title')
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('main_industry_id')
                    ->relationship('mainIndustry', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('tags')
                    ->relationship('tags', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
            ])
            ->paginated([12])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('title')
                        ->label(__('filament-resources.events.fields.title'))
                        ->searchable()
                        ->sortable()
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large),

                    Tables\Columns\Layout\Grid::make(2)
                        ->schema([
                            Tables\Columns\TextColumn::make('city.title')
                                ->label(__('filament-resources.events.fields.city_id'))
                                ->icon('heroicon-m-map-pin'),

                            Tables\Columns\TextColumn::make('format')
                                ->label(__('filament-resources.events.fields.format'))
                                ->badge()
                                ->formatStateUsing(fn(?string $state): string => $state ? __("filament-resources.events.formats.{$state}") : ''
                                ),
                        ]),

                    Tables\Columns\Layout\Grid::make(2)
                        ->schema([
                            Tables\Columns\TextColumn::make('start_date')
                                ->label(__('filament-resources.events.fields.start_date'))
                                ->date()
                                ->icon('heroicon-m-calendar')
                                ->iconPosition(IconPosition::Before),


                            Tables\Columns\TextColumn::make('mainIndustry.title')
                                ->label(__('filament-resources.events.fields.main_industry_id'))
                                ->icon('heroicon-m-building-library'),
                        ]),

                    Tables\Columns\Layout\Grid::make(2)
                        ->schema([
                            Tables\Columns\TextColumn::make('website')
                                ->label(__('filament-resources.events.fields.website'))
                                ->url(fn($record): ?string => $record?->website)
                                ->openUrlInNewTab()
                                ->icon('heroicon-m-globe-alt')
                                ->visible(fn($record): bool => filled($record?->website)),
                        ]),


                ])
                    ->space(3)

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('format'),
                Tables\Filters\SelectFilter::make('city')
                    ->relationship('city', 'title'),
                Tables\Filters\SelectFilter::make('mainIndustry')
                    ->relationship('mainIndustry', 'title'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add your relations here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
