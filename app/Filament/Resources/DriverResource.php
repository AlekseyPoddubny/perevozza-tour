<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Filament\Resources\DriverResource\RelationManagers;
use App\Models\Driver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DriverResource extends Resource
{
    protected static ?string $modelLabel = 'водитель';
    protected static ?string $pluralModelLabel = 'водители';
    protected static ?string $navigationLabel = 'Водители';
    protected static ?string $navigationGroup = 'Ресурсы';
    protected static ?int $navigationSort = 2;

    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('full_name')
                ->label('ФИО водителя')
                ->required(),
            Forms\Components\TextInput::make('phone')
                ->label('Телефон')
                ->tel()
                ->required(),
            Forms\Components\Textarea::make('additional_info')
                ->label('Дополнительная информация')
                ->placeholder('Стаж, категории, примечания...')
                ->columnSpanFull(),
            Forms\Components\FileUpload::make('photo')
                ->label('Фото')
                ->image()
                ->directory('drivers')
                ->disk('public')
                ->visibility('public')
                ->imageEditor(),
            Forms\Components\Toggle::make('is_active')
                ->label('Работает')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('ФИО')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Статус')
                    ->boolean(),
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
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }
}
