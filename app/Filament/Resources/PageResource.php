<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Страницы';
    protected static ?string $navigationGroup = 'Управление';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Заголовок страницы')
                        ->required(),
                    Forms\Components\TextInput::make('slug')
                        ->label('Ярлык (URL)')
                        //->disabled() // Чтобы не сломать ссылки случайно
                        ->required(),
                    Forms\Components\RichEditor::make('content')
                        ->label('Контент')
                        ->toolbarButtons([
                            'blockquote',
                            'bold',
                            'bulletList',
                            'h2',
                            'h3',
                            'italic',
                            'orderedList',
                            'redo',
                            'undo',
                        ])
                        ->required()
                        ->columnSpanFull(),
                    // Дополнительные поля для страницы контактов (будут видны, если slug == contacts)
                    Forms\Components\KeyValue::make('metadata')
                        ->label('Дополнительные данные (Телефон, Email, Карта)')
                        ->helperText('Используйте для хранения системных строк, например: phone, address')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Страница'),
                Tables\Columns\TextColumn::make('slug')->label('URL'),
                Tables\Columns\TextColumn::make('updated_at')->label('Обновлено')->dateTime(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
