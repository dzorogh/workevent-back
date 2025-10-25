<?php

namespace App\Filament\Resources\Presets;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Presets\Pages\ListPresets;
use App\Filament\Resources\Presets\Pages\CreatePreset;
use App\Filament\Resources\Presets\Pages\EditPreset;
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
use App\Filament\Traits\HasMetadataResource;

class PresetResource extends Resource
{
    use HasMetadataResource;

    protected static ?string $model = Preset::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-funnel';

    protected static string | \UnitEnum | null $navigationGroup = 'references';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('filament-resources.presets.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.presets.plural_label');
    }

    public static function getResourceFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('filament-resources.presets.fields.title'))
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->label(__('filament-resources.presets.fields.slug'))
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Section::make(__('filament-resources.presets.sections.filters'))
                ->schema([
                    Select::make('filters.format')
                        ->label(__('filament-resources.events.fields.format'))
                        ->options(EventFormat::getLabels()),

                    Select::make('filters.city_id')
                        ->label(__('filament-resources.events.fields.city_id'))
                        ->options(City::pluck('title', 'id')),

                    Select::make('filters.industry_id')
                        ->label(__('filament-resources.events.fields.industry_id'))
                        ->options(Industry::pluck('title', 'id')),
                ])
                ->columns(3),

            Toggle::make('is_active')
                ->label(__('filament-resources.presets.fields.is_active'))
                ->default(true),

            MarkdownEditor::make('description')
                ->label(__('filament-resources.presets.fields.description')), 
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-resources.presets.fields.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('filament-resources.presets.fields.slug'))
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label(__('filament-resources.presets.fields.is_active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('filament-resources.presets.fields.is_active')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('duplicate')
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPresets::route('/'),
            'create' => CreatePreset::route('/create'),
            'edit' => EditPreset::route('/{record}/edit'),
        ];
    }
}
