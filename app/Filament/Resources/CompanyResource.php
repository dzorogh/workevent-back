<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers\EventsRelationManager;
use App\Models\Company;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\ParticipationType;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'organizations';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('filament-resources.companies.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.companies.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-resources.companies.fields.title'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('inn')
                            ->label(__('filament-resources.companies.fields.inn'))
                            ->maxLength(12)
                            ->unique(ignoreRecord: true)
                            ->numeric()
                            ->rules(['regex:/^\d{10}(\d{2})?$/'])
                            ->helperText(__('filament-resources.companies.fields.inn_help')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament-resources.companies.sections.contacts'))
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->label(__('filament-resources.companies.fields.website'))
                            ->url()
                            ->suffixIcon('heroicon-m-globe-alt'),

                        PhoneInput::make('phone')
                            ->label(__('filament-resources.companies.fields.phone'))
                            ->defaultCountry('RU')
                            ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL),

                        Forms\Components\TextInput::make('email')
                            ->label(__('filament-resources.companies.fields.email'))
                            ->email()
                            ->suffixIcon('heroicon-m-envelope'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make()
                    ->schema([
                        MarkdownEditor::make('description')
                            ->label(__('filament-resources.companies.fields.description'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.companies.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('inn')
                    ->label(__('filament-resources.companies.fields.inn'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.companies.fields.events_count'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('website')
                    ->label(__('filament-resources.companies.fields.website'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn (Company $record) => $record->website),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('filament-resources.companies.fields.phone'))
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-resources.companies.fields.email'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('participation_type')
                    ->relationship('events', 'participation_type')
                    ->options(ParticipationType::getLabels())
                    ->multiple()
                    ->label(__('filament-resources.companies.fields.participation_type')),

                Tables\Filters\Filter::make('has_inn')
                    ->query(fn ($query) => $query->whereNotNull('inn'))
                    ->label('С ИНН')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
//            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
