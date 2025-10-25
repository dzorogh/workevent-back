<?php

namespace App\Filament\Traits;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Group;
use Filament\Forms;
use App\Filament\Forms\Components\MetadataForm;
use Illuminate\Database\Eloquent\Builder;

trait HasMetadataResource
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('metadata');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make(__('filament.tabs.main'))
                            ->schema(static::getResourceFormSchema()),
                        Tab::make(__('filament.tabs.metadata'))
                            ->schema([
                                Group::make(MetadataForm::make())
                                    ->relationship('metadata')
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    abstract public static function getResourceFormSchema(): array;
}
