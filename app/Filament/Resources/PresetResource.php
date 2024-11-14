<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresetResource\Pages;
use App\Models\Preset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\City;
use App\Enums\EventFormat;
use App\Models\Industry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class PresetResource extends Resource
{
    protected static ?string $model = Preset::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationGroup = 'references';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('filament-resources.presets.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.presets.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('filament-resources.presets.fields.title'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label(__('filament-resources.presets.fields.slug'))
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Section::make(__('filament-resources.presets.sections.filters'))
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
                    ->label(__('filament-resources.presets.fields.is_active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.presets.fields.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('filament-resources.presets.fields.slug'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-resources.presets.fields.is_active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-resources.presets.fields.is_active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Дублировать')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (Preset $record): void {
                        $clone = $record->replicate();
                        $clone->title = $clone->title . ' (копия)';
                        $clone->slug = $clone->slug . '-copy';
                        $clone->save();
                        
                        Notification::make()
                            ->title('Пресет скопирован')
                            ->success()
                            ->send();
                    })
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
            'index' => Pages\ListPresets::route('/'),
            'create' => Pages\CreatePreset::route('/create'),
            'edit' => Pages\EditPreset::route('/{record}/edit'),
        ];
    }
}
