<?php

namespace App\Filament\Resources\Companies;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\Companies\RelationManagers\EventsRelationManager;
use App\Models\Company;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\ParticipationType;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static string | \UnitEnum | null $navigationGroup = 'participants';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('filament-resources.companies.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.companies.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament-resources.companies.fields.title'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('inn')
                            ->label(__('filament-resources.companies.fields.inn'))
                            ->maxLength(12)
                            ->unique(ignoreRecord: true)
                            ->numeric()
                            ->rules(['regex:/^\d{10}(\d{2})?$/'])
                            ->helperText(__('filament-resources.companies.fields.inn_help')),
                    ])
                    ->columns(2),

                Section::make(__('filament-resources.companies.sections.contacts'))
                    ->schema([
                        TextInput::make('website')
                            ->label(__('filament-resources.companies.fields.website'))
                            ->url()
                            ->suffixIcon('heroicon-m-globe-alt'),

                        PhoneInput::make('phone')
                            ->label(__('filament-resources.companies.fields.phone'))
                            ->defaultCountry('RU')
                            ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL),

                        TextInput::make('email')
                            ->label(__('filament-resources.companies.fields.email'))
                            ->email()
                            ->suffixIcon('heroicon-m-envelope'),
                    ])
                    ->columns(3),

                Section::make()
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
                TextColumn::make('title')
                    ->label(__('filament-resources.companies.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('inn')
                    ->label(__('filament-resources.companies.fields.inn'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('events_count')
                    ->counts('events')
                    ->label(__('filament-resources.companies.fields.events_count'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('website')
                    ->label(__('filament-resources.companies.fields.website'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->url(fn (Company $record) => $record->website),

                TextColumn::make('phone')
                    ->label(__('filament-resources.companies.fields.phone'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email')
                    ->label(__('filament-resources.companies.fields.email'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('participation_type')
                    ->relationship('events', 'participation_type')
                    ->options(ParticipationType::getLabels())
                    ->multiple()
                    ->label(__('filament-resources.companies.fields.participation_type')),

                Filter::make('has_inn')
                    ->query(fn ($query) => $query->whereNotNull('inn'))
                    ->label('С ИНН')
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
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
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
//            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
}
