<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\Author;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->isAdmin()) {
            return $query;
        }
        return $query->where('author_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('author_id')
                    ->relationship('author', 'username')
                    ->required()
                    ->options (function () {
                     $user = auth()->user();
                        if ($user->isAdmin()) {
                            return Author::pluck('username', 'id'); 
                        } else if ($user->author()) {
                        return [$user->author->id => $user->author->username]; 
                        }
                        return [];
                    })
                    ->default (function () {
                        $user = auth()->user();
                        return $user->author ? $user->author->id : null;
                    })
                    ->disabled (function () {
                        $user = auth()->user();
                        return !$user->isAdmin();
                    }),       
                Forms\Components\Select::make('news_category_id')
                    ->relationship('newsCategory', 'title')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->readOnly(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_featured')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author.username'),
                Tables\Columns\TextColumn::make('newsCategory.title'),
                Tables\Columns\TextColumn::make('title')->sortable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\ToggleColumn::make('is_featured')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('author_id')
                    ->relationship('author', 'username')
                    ->label('Select Author'),
                Tables\Filters\SelectFilter::make('news_category_id')
                    ->relationship('newsCategory', 'title')
                    ->label('Select Category'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
