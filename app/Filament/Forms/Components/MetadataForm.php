<?php

namespace App\Filament\Forms\Components;

use Filament\Forms;

class MetadataForm
{
    public static function make(): array
    {
        return [
            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Meta Title'),
                    Forms\Components\TextInput::make('h1')
                        ->label('H1 заголовок'),
                    Forms\Components\Textarea::make('description')
                        ->label('Meta Description'),
                    Forms\Components\TextInput::make('keywords')
                        ->label('Meta Keywords')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\Select::make('robots')
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

            Forms\Components\Section::make('Open Graph')
                ->schema([
                    Forms\Components\TextInput::make('og_title')
                        ->label('Title')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('og_description')
                        ->label('Description'),
                    Forms\Components\Select::make('og_type')
                        ->label('Type')
                        ->options([
                            'website' => 'Website',
                            'article' => 'Article',
                            'product' => 'Product',
                        ])
                        ->default('website'),
                    Forms\Components\FileUpload::make('og_image')
                        ->label('Image')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9'),
                    Forms\Components\TextInput::make('og_url')
                        ->label('URL')
                        ->url(),
                    Forms\Components\TextInput::make('og_site_name')
                        ->label('Site Name'),
                    Forms\Components\Select::make('og_locale')
                        ->label('Locale')
                        ->options([
                            'ru_RU' => 'Русский',
                            'en_US' => 'English',
                        ])
                        ->default('ru_RU'),
                ])
                ->columnSpan(['lg' => 2]),

            Forms\Components\Section::make('Twitter Card')
                ->schema([
                    Forms\Components\Select::make('tw_card')
                        ->label('Card Type')
                        ->options([
                            'summary' => 'Summary',
                            'summary_large_image' => 'Summary Large Image',
                        ])
                        ->default('summary_large_image'),
                    Forms\Components\TextInput::make('tw_title')
                        ->label('Title'),
                    Forms\Components\Textarea::make('tw_description')
                        ->label('Description'),
                    Forms\Components\FileUpload::make('tw_image')
                        ->label('Image')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('2:1'),
                    Forms\Components\TextInput::make('tw_site')
                        ->label('Site (@username)'),
                    Forms\Components\TextInput::make('tw_creator')
                        ->label('Creator (@username)'),
                ])
                ->columnSpan(['lg' => 2])
        ];
    }
}
