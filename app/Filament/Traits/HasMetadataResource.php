<?php

namespace App\Filament\Traits;

use Filament\Forms;
use App\Filament\Forms\Components\MetadataForm;
use Illuminate\Database\Eloquent\Builder;

trait HasMetadataResource
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('metadata');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('filament.tabs.main'))
                            ->schema(static::getResourceFormSchema()),
                        Forms\Components\Tabs\Tab::make(__('filament.tabs.metadata'))
                            ->schema([
                                Forms\Components\Group::make(MetadataForm::make())
                                    ->relationship('metadata')
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    abstract public static function getResourceFormSchema(): array;
}
