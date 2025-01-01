<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Traits\HasMetadataResource;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PostResource extends Resource
{
    use HasMetadataResource;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getModelLabel(): string
    {
        return __('filament-resources.posts.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-resources.posts.plural_label');
    }

    public static function getResourceFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label(__('filament-resources.posts.fields.title'))
                ->required()
                ->maxLength(255),

            SpatieMediaLibraryFileUpload::make('cover')
                ->label(__('filament-resources.posts.fields.cover'))
                ->collection('cover')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios([
                    '16:9',
                ])
                ->columnSpanFull(),

            Forms\Components\MarkdownEditor::make('content')
                ->label(__('filament-resources.posts.fields.content'))
                ->required()
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-resources.posts.fields.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-resources.posts.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-resources.posts.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('filament-resources.posts.fields.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament-resources.posts.fields.user'))
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
