<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'references';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('filament-resources.venues.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.venues.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.venues.fields.title'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('address')
                            ->label(__('filament-resources.venues.fields.address'))
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament-resources.venues.sections.contacts'))
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->label(__('filament-resources.venues.fields.website'))
                            ->url()
                            ->suffixIcon('heroicon-m-globe-alt'),

                        PhoneInput::make('phone')
                            ->label(__('filament-resources.venues.fields.phone'))
                            ->defaultCountry('RU')
                            ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL),

                        Forms\Components\TextInput::make('email')
                            ->label(__('filament-resources.venues.fields.email'))
                            ->email()
                            ->suffixIcon('heroicon-m-envelope'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make()
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
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.venues.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('address')
                    ->label(__('filament-resources.venues.fields.address'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.venues.fields.events_count'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('website')
                    ->label(__('filament-resources.venues.fields.website'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn ($record) => $record->website),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('filament-resources.venues.fields.phone'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-resources.venues.fields.email'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}
