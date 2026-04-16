<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
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

class VehicleResource extends Resource
{
    protected static ?string $modelLabel = 'автомобиль';
    protected static ?string $pluralModelLabel = 'автомобили';
    protected static ?string $navigationLabel = 'Автомобили';
    protected static ?string $navigationGroup = 'Ресурсы';
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Автомобиль')->schema([
                Forms\Components\TextInput::make('make_model')
                    ->required()->label('Марка и модель'),
                Forms\Components\FileUpload::make('image')
                    ->image()->directory('vehicles')->label('Фото'),
                Forms\Components\TextInput::make('seats')
                    ->numeric()->required()->label('Мест'),
                Forms\Components\TextInput::make('license_plate')
                    ->label('Госномер'),
            ])->columns(2),

            Forms\Components\Section::make('Настройки')->schema([
                Forms\Components\Textarea::make('description')
                    ->label('Описание удобств')->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Показывать на сайте')->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()->default(0)->label('Порядок сортировки'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('image')->label('Фото'),
            Tables\Columns\TextColumn::make('make_model')->label('Модель')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('seats')->label('Мест'),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label('Активен'),
            Tables\Columns\TextColumn::make('sort_order')->label('Приоритет')->sortable(),
        ])->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }

}
