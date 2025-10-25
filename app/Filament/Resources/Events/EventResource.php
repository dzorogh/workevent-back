<?php

namespace App\Filament\Resources\Events;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Traits\HasMetadataResource;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\Series;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use App\Enums\EventFormat;
use App\Filament\Resources\Events\RelationManagers\TariffsRelationManager;
use App\Filament\Forms\Components\MetadataForm;
use Filament\Tables\Filters\DateFilter;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Carbon\Carbon;

class EventResource extends Resource
{
    use HasMetadataResource;

    protected static ?string $model = Event::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar';

    protected static string | \UnitEnum | null $navigationGroup = 'events';

    public static function getModelLabel(): string
    {
        return __('filament-resources.events.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.events.plural_label');
    }

    public static function getResourceFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('filament-resources.events.fields.title'))
                ->required()
                ->columnSpanFull()
                ->maxLength(255),

            Section::make()
                ->schema([


                    Select::make('format')
                        ->label(__('filament-resources.events.fields.format'))
                        ->options(EventFormat::getLabels())
                        ->required(),

                    Select::make('series_id')
                        ->label(__('filament-resources.events.fields.series_id'))
                        ->relationship('series', 'title')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('title')
                                ->label(__('filament-resources.event-series.fields.title'))
                                ->required()
                                ->maxLength(255)
                                ->unique(Series::class),
                        ])
                        ->createOptionModalHeading(__('filament-resources.event-series.actions.create.heading')),

                    Select::make('industry_id')
                        ->label(__('filament-resources.events.fields.industry_id'))
                        ->relationship('industry', 'title')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('title')
                                ->label(__('filament-resources.industries.fields.title'))
                                ->required()
                                ->maxLength(255)
                                ->unique('industries', 'title'),
                        ])
                        ->createOptionModalHeading(__('filament-resources.industries.actions.create.heading')),

                    Select::make('industries')
                        ->label(__('filament-resources.events.fields.industries'))
                        ->relationship('industries', 'title')
                        ->searchable()
                        ->preload()
                        ->multiple()
                        ->createOptionForm([
                            TextInput::make('title')
                                ->label(__('filament-resources.industries.fields.title'))
                                ->required()
                                ->maxLength(255)
                                ->unique('industries', 'title'),
                        ])
                        ->createOptionModalHeading(__('filament-resources.industries.actions.create.heading')),
                ])
                ->columns(2),

            Section::make(__('filament-resources.events.sections.location'))
                ->schema([
                    Select::make('city_id')
                        ->label(__('filament-resources.events.fields.city_id'))
                        ->relationship('city', 'title')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('title')
                                ->label(__('filament-resources.cities.fields.title'))
                                ->required()
                                ->maxLength(255)
                                ->unique('cities', 'title'),
                        ])
                        ->createOptionModalHeading(__('filament-resources.cities.actions.create.heading')),

                    Select::make('venue_id')
                        ->label(__('filament-resources.events.fields.venue'))
                        ->relationship('venue', 'title')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('title')
                                ->label(__('filament-resources.venues.fields.title'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('address')
                                ->label(__('filament-resources.venues.fields.address'))
                                ->maxLength(255),
                        ]),
                ])
                ->columns(2),

            Section::make(__('filament-resources.events.sections.dates'))
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

                    DatePicker::make('end_date')
                        ->label(__('filament-resources.events.fields.end_date'))
                        ->native(false)
                        ->displayFormat('d.m.Y')
                        ->afterOrEqual('start_date')
                        ->closeOnDateSelection(),
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
                ->imageEditorAspectRatios([
                    '16:9',
                ])
                ->imageEditorMode(2)

                ->columnSpanFull()
                ->downloadable()
                ->panelLayout('grid')
                ->removeUploadedFileButtonPosition('right')
                ->uploadProgressIndicatorPosition('left'),

            MarkdownEditor::make('description')
                ->label(__('filament-resources.events.fields.description'))
                ->columnSpanFull(),



            Select::make('tags')
                ->label(__('filament-resources.events.fields.tags'))
                ->relationship('tags', 'title')
                ->multiple()
                ->preload()
                ->searchable()
                ->createOptionForm([
                    TextInput::make('title')
                        ->label(__('filament-resources.event-tags.fields.title'))
                        ->required()
                        ->maxLength(255)
                        ->unique('tags', 'title'),
                ])
                ->createOptionModalHeading(__('filament-resources.event-tags.actions.create.heading'))
                ->placeholder(__('filament-resources.events.placeholders.tags')),


            Section::make(__('filament-resources.events.sections.settings'))
                ->schema([
                    Toggle::make('is_priority')
                        ->label(__('filament-resources.events.fields.is_priority'))
                        ->helperText(__('filament-resources.events.fields.is_priority_help')),

                    TextInput::make('sort_order')
                        ->label(__('filament-resources.events.fields.sort_order'))
                        ->numeric()
                        ->default(0)
                        ->helperText(__('filament-resources.events.fields.sort_order_help')),
                ])
                ->columns(2),

            Section::make(__('filament-resources.events.sections.contacts'))
                ->schema([
                    TextInput::make('website')
                        ->label(__('filament-resources.events.fields.website'))
                        ->url()
                        ->suffixIcon('heroicon-m-globe-alt'),

                    PhoneInput::make('phone')
                        ->label(__('filament-resources.events.fields.phone'))
                        ->defaultCountry('RU')
                        ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL),

                    TextInput::make('email')
                        ->label(__('filament-resources.events.fields.email'))
                        ->email()
                        ->suffixIcon('heroicon-m-envelope'),
                ])
                ->columns(3),
        ];
    }

    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->reorderable('sort_order')
            // ->defaultSort('start_date', 'asc')
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
            ])
            ->paginated([12])
            ->columns([
                Stack::make([

                    TextColumn::make('title')
                        ->label(__('filament-resources.events.fields.title'))
                        ->searchable()
                        ->sortable()
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large),

                    Grid::make(2)
                        ->schema([
                            TextColumn::make('format')
                                ->label(__('filament-resources.events.fields.format'))
                                ->badge()
                                ->color(fn(EventFormat $state): string => $state->getColor())
                                ->formatStateUsing(fn(EventFormat $state): string => $state->getLabel()),

                            TextColumn::make('city.title')
                                ->label(__('filament-resources.events.fields.city_id'))
                                ->icon('heroicon-m-map-pin'),
                        ]),

                    Grid::make(2)
                        ->schema([
                            TextColumn::make('start_date')
                                ->sortable()
                                ->label(__('filament-resources.events.fields.start_date'))
                                ->date('d.m.Y')
                                ->icon('heroicon-m-calendar')
                                ->iconPosition(IconPosition::Before),

                            TextColumn::make('industry.title')
                                ->label(__('filament-resources.events.fields.industry_id'))
                                ->icon('heroicon-m-building-library'),
                        ]),
                ])
                    ->space(3),
            ])
            ->filters([
                SelectFilter::make('format')
                    ->label(__('filament-resources.events.fields.format'))
                    ->options(EventFormat::getLabels()),
                SelectFilter::make('city')
                    ->label(__('filament-resources.events.fields.city_id'))
                    ->relationship('city', 'title'),
                SelectFilter::make('industry')
                    ->label(__('filament-resources.events.fields.industry_id'))
                    ->relationship('industry', 'title'),
                DateRangeFilter::make('start_date')
                    ->label(__('filament-resources.events.fields.start_date'))
                    ->defaultCustom(Carbon::now(), Carbon::now()->endOfYear()),
                TernaryFilter::make('is_priority')
                    ->label(__(key: 'filament-resources.events.fields.is_priority')),
                TrashedFilter::make('trashed')
                    ->label(__('filament-resources.events.fields.trashed')),
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
