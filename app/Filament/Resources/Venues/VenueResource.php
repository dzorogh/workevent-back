<?php

namespace App\Filament\Resources\Venues;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Venues\Pages\ListVenues;
use App\Filament\Resources\Venues\Pages\CreateVenue;
use App\Filament\Resources\Venues\Pages\EditVenue;
use App\Filament\Resources\VenueResource\Pages;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string | \UnitEnum | null $navigationGroup = 'references';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('filament-resources.venues.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.venues.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament-resources.venues.fields.title'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('address')
                            ->label(__('filament-resources.venues.fields.address'))
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make(__('filament-resources.venues.sections.contacts'))
                    ->schema([
                        TextInput::make('website')
                            ->label(__('filament-resources.venues.fields.website'))
                            ->url()
                            ->suffixIcon('heroicon-m-globe-alt'),

                        PhoneInput::make('phone')
                            ->label(__('filament-resources.venues.fields.phone'))
                            ->defaultCountry('RU')
                            ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL),

                        TextInput::make('email')
                            ->label(__('filament-resources.venues.fields.email'))
                            ->email()
                            ->suffixIcon('heroicon-m-envelope'),
                    ])
                    ->columns(3),

                Section::make()
                    ->schema([
                        MarkdownEditor::make('description')
                            ->label(__('filament-resources.venues.fields.description'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-resources.venues.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('address')
                    ->label(__('filament-resources.venues.fields.address'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.venues.fields.events_count'))
                    ->sortable(),

                TextColumn::make('website')
                    ->label(__('filament-resources.venues.fields.website'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn ($record) => $record->website),

                TextColumn::make('phone')
                    ->label(__('filament-resources.venues.fields.phone'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email')
                    ->label(__('filament-resources.venues.fields.email'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVenues::route('/'),
            'create' => CreateVenue::route('/create'),
            'edit' => EditVenue::route('/{record}/edit'),
        ];
    }
}
