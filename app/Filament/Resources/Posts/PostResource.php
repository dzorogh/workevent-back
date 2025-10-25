<?php

namespace App\Filament\Resources\Posts;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

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
            TextInput::make('title')
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

            MarkdownEditor::make('content')
                ->label(__('filament-resources.posts.fields.content'))
                ->required()
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-resources.posts.fields.title'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('filament-resources.posts.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                    ->label(__('filament-resources.posts.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('filament-resources.posts.fields.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label(__('filament-resources.posts.fields.user'))
            ])
            ->filters([
                //
            ])
            ->recordActions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
