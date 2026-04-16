<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RouteResource\Pages;
use App\Filament\Resources\RouteResource\RelationManagers;
use App\Models\Route;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;


class RouteResource extends Resource
{
    protected static ?string $modelLabel = 'маршрут';
    protected static ?string $pluralModelLabel = 'маршруты';
    protected static ?string $navigationLabel = 'Маршруты';
    protected static ?string $navigationGroup = 'Логистика';
    protected static ?int $navigationSort = 3;

    protected static ?string $model = Route::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основная информация')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Описание маршрута')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true),
                    ]),

                Section::make('Маршрут следования')
                    ->schema([
                        Forms\Components\Repeater::make('cityRoute') // Используем hasMany к промежуточной таблице
                        ->relationship()
                            ->schema([
                                Forms\Components\Select::make('city_id')
                                    ->label('Город')
                                    ->relationship('city', 'name') // Связь внутри модели CityRoute к City
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ])
                            ->orderColumn('order')
                            ->defaultItems(1)
                            ->reorderableWithButtons()
                            ->addActionLabel('Добавить остановку'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Показываем первую точку маршрута (Откуда)
                Tables\Columns\TextColumn::make('cities.name')
                    ->label('Маршрут')
                    ->getStateUsing(fn ($record) =>
                        $record->cities->first()?->name . ' ➔ ' . $record->cities->last()?->name
                    )
                    ->description(fn ($record) => "Всего остановок: " . $record->cities->count()),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Статус')
                    ->boolean(),
            ])
            ->filters([
                // Тут можно будет добавить фильтры позже
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRoutes::route('/'),
            'create' => Pages\CreateRoute::route('/create'),
            'edit' => Pages\EditRoute::route('/{record}/edit'),
        ];
    }
}
