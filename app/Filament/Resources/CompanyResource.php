<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers\EventsRelationManager;
use App\Models\Company;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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

                Forms\Components\Section::make(__('filament-resources.companies.sections.event_participations'))
                    ->schema([
                        Forms\Components\Repeater::make('event_participations')
                            ->schema([
                                Forms\Components\Select::make('event_id')
                                    ->label(__('filament-resources.companies.fields.event'))
                                    ->options(fn () => Event::pluck('title', 'id'))
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Select::make('participation_type')
                                    ->options([
                                        'organizer' => __('filament-resources.companies.participation_types.organizer'),
                                        'sponsor' => __('filament-resources.companies.participation_types.sponsor'),
                                        'participant' => __('filament-resources.companies.participation_types.participant'),
                                        'partner' => __('filament-resources.companies.participation_types.partner'),
                                    ])
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->addActionLabel('Add Event Participation')
                            ->collapsible(),
                    ])
                    ->collapsible(),
            ]);
    }

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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('participation_type')
                    ->relationship('events', 'participation_type')
                    ->options([
                        'organizer' => 'Organizer',
                        'sponsor' => 'Sponsor',
                        'participant' => 'Participant',
                        'partner' => 'Partner',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('has_inn')
                    ->query(fn ($query) => $query->whereNotNull('inn'))
                    ->label('With INN')
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
