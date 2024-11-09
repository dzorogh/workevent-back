<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\Series;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use App\Models\Tag;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use App\Enums\EventFormat;
use App\Filament\Resources\EventResource\RelationManagers\TariffsRelationManager;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'events';

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
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.events.fields.title'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('format')
                            ->label(__('filament-resources.events.fields.format'))
                            ->options(EventFormat::getLabels())
                            ->required(),

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
                                    ->unique(Series::class),
                            ])
                            ->createOptionModalHeading(__('filament-resources.event-series.actions.create.heading')),
                    ])
                    ->columns(2),

                SpatieMediaLibraryFileUpload::make('cover')
                    ->label(__('filament-resources.events.fields.cover'))
                    ->collection('cover')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                    ])
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('gallery')
                    ->label(__('filament-resources.events.fields.gallery'))
                    ->collection('gallery')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->imageEditor()
                    ->maxFiles(10)
                    ->columnSpanFull()
                    ->downloadable()
                    ->panelLayout('grid')
                    ->panelAspectRatio('16:9')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadProgressIndicatorPosition('left')
                    ->hint(__('filament-resources.events.hints.gallery')),

                MarkdownEditor::make('description')
                    ->label(__('filament-resources.events.fields.description'))
                    ->columnSpanFull(),

                Forms\Components\Grid::make()
                    ->schema([
                        DatePicker::make('start_date')
                            ->label(__('filament-resources.events.fields.start_date'))
                            ->native(false)
                            ->live()
                            ->displayFormat('d.m.Y')
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                if (blank($get('end_date'))) {
                                    $set('end_date', $get('start_date'));
                                }
                            })
                            ->closeOnDateSelection(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label(__('filament-resources.events.fields.end_date'))
                            ->native(false)
                            ->displayFormat('d.m.Y')
                            ->afterOrEqual('start_date')
                            ->closeOnDateSelection(),
                    ])
                    ->columns(2),

                Forms\Components\Select::make('industry_id')
                    ->label(__('filament-resources.events.fields.industry_id'))
                    ->relationship('industry', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.industries.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->unique('industries', 'title'),
                    ])
                    ->createOptionModalHeading(__('filament-resources.industries.actions.create.heading')),

                Select::make('tags')
                    ->label(__('filament-resources.events.fields.tags'))
                    ->relationship('tags', 'title')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.event-tags.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->unique('tags', 'title'),
                    ])
                    ->createOptionModalHeading(__('filament-resources.event-tags.actions.create.heading'))
                    ->placeholder(__('filament-resources.events.placeholders.tags')),

                Forms\Components\Section::make(__('filament-resources.events.sections.location'))
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label(__('filament-resources.events.fields.city_id'))
                            ->relationship('city', 'title')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament-resources.cities.fields.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('cities', 'title'),
                            ])
                            ->createOptionModalHeading(__('filament-resources.cities.actions.create.heading')),

                        Forms\Components\Select::make('venue_id')
                            ->label(__('filament-resources.events.fields.venue'))
                            ->relationship('venue', 'title')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament-resources.venues.fields.title'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('address')
                                    ->label(__('filament-resources.venues.fields.address'))
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament-resources.events.sections.settings'))
                    ->schema([
                        Toggle::make('is_priority')
                            ->label(__('filament-resources.events.fields.is_priority'))
                            ->helperText(__('filament-resources.events.fields.is_priority_help')),

                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('filament-resources.events.fields.sort_order'))
                            ->numeric()
                            ->default(0)
                            ->helperText(__('filament-resources.events.fields.sort_order_help')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament-resources.events.sections.contacts'))
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->label(__('filament-resources.events.fields.website'))
                            ->url()
                            ->suffixIcon('heroicon-m-globe-alt'),

                        PhoneInput::make('phone')
                            ->label(__('filament-resources.events.fields.phone'))
                            ->defaultCountry('RU')
                            ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL),

                        Forms\Components\TextInput::make('email')
                            ->label(__('filament-resources.events.fields.email'))
                            ->email()
                            ->suffixIcon('heroicon-m-envelope'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'desc')
            ->reorderable('sort_order')
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
            ])
            ->paginated([12])
            ->columns([
                Stack::make([

                    Tables\Columns\TextColumn::make('title')
                        ->label(__('filament-resources.events.fields.title'))
                        ->searchable()
                        ->sortable()
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large),

                    Tables\Columns\Layout\Grid::make(2)
                        ->schema([
                            Tables\Columns\TextColumn::make('format')
                                ->label(__('filament-resources.events.fields.format'))
                                ->badge()
                                ->color(fn (EventFormat $state): string => $state->getColor())
                                ->formatStateUsing(fn (EventFormat $state): string => $state->getLabel()),

                            Tables\Columns\TextColumn::make('city.title')
                                ->label(__('filament-resources.events.fields.city_id'))
                                ->icon('heroicon-m-map-pin'),
                        ]),

                    Tables\Columns\Layout\Grid::make(2)
                        ->schema([
                            Tables\Columns\TextColumn::make('start_date')
                                ->label(__('filament-resources.events.fields.start_date'))
                                ->date('d.m.Y')
                                ->icon('heroicon-m-calendar')
                                ->iconPosition(IconPosition::Before),

                            Tables\Columns\TextColumn::make('industry.title')
                                ->label(__('filament-resources.events.fields.industry_id'))
                                ->icon('heroicon-m-building-library'),
                        ]),
                ])
                    ->space(3),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('format')
                    ->options(EventFormat::getLabels()),
                Tables\Filters\SelectFilter::make('city')
                    ->relationship('city', 'title'),
                Tables\Filters\SelectFilter::make('industry')
                    ->relationship('industry', 'title'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TariffsRelationManager::class,
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
