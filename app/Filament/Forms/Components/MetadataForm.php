<?php

namespace App\Filament\Forms\Components;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms;

class MetadataForm
{
    public static function make(): array
    {
        return [
            Section::make('SEO')
                ->schema([
                    TextInput::make('title')
                        ->label('Meta Title'),
                    TextInput::make('h1')
                        ->label('H1 заголовок'),
                    Textarea::make('description')
                        ->label('Meta Description'),
                    TextInput::make('keywords')
                        ->label('Meta Keywords')
                        ->maxLength(255),
                    TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->url()
                        ->maxLength(255),
                    Select::make('robots')
                        ->label('Robots')
                        ->options([
                            'index,follow' => 'Index, Follow',
                            'noindex,follow' => 'No Index, Follow',
                            'index,nofollow' => 'Index, No Follow',
                            'noindex,nofollow' => 'No Index, No Follow',
                        ])
                        ->default('index,follow'),
                ])
                ->columnSpan(['lg' => 2]),

            Section::make('Open Graph')
                ->schema([
                    TextInput::make('og_title')
                        ->label('Title')
                        ->maxLength(255),
                    Textarea::make('og_description')
                        ->label('Description'),
                    Select::make('og_type')
                        ->label('Type')
                        ->options([
                            'website' => 'Website',
                            'article' => 'Article',
                            'product' => 'Product',
                        ])
                        ->default('website'),
                    FileUpload::make('og_image')
                        ->label('Image')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9'),
                    TextInput::make('og_url')
                        ->label('URL')
                        ->url(),
                    TextInput::make('og_site_name')
                        ->label('Site Name'),
                    Select::make('og_locale')
                        ->label('Locale')
                        ->options([
                            'ru_RU' => 'Русский',
                            'en_US' => 'English',
                        ])
                        ->default('ru_RU'),
                ])
                ->columnSpan(['lg' => 2]),

            Section::make('Twitter Card')
                ->schema([
                    Select::make('tw_card')
                        ->label('Card Type')
                        ->options([
                            'summary' => 'Summary',
                            'summary_large_image' => 'Summary Large Image',
                        ])
                        ->default('summary_large_image'),
                    TextInput::make('tw_title')
                        ->label('Title'),
                    Textarea::make('tw_description')
                        ->label('Description'),
                    FileUpload::make('tw_image')
                        ->label('Image')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('2:1'),
                    TextInput::make('tw_site')
                        ->label('Site (@username)'),
                    TextInput::make('tw_creator')
                        ->label('Creator (@username)'),
                ])
                ->columnSpan(['lg' => 2])
        ];
    }
}
