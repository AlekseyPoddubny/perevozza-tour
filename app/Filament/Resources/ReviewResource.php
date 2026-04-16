<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Отзывы';
    protected static ?string $navigationGroup = 'Управление';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('author_name')->required()->label('Имя'),
                Forms\Components\Select::make('rating')
                    ->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5])->default(5)->label('Оценка'),
                Forms\Components\Textarea::make('content')->required()->label('Текст отзыва')->columnSpanFull(),
                Forms\Components\Toggle::make('is_published')->label('Опубликован'),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('author_name')->label('Автор')->searchable(),
            Tables\Columns\TextColumn::make('content')->label('Текст отзыва'),
            Tables\Columns\TextColumn::make('rating')->label('Оценка'),
            Tables\Columns\IconColumn::make('is_published')->boolean()->label('Виден'),
            Tables\Columns\TextColumn::make('created_at')->label('Дата')->date(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
