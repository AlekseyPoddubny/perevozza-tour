<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class BookingResource extends Resource
{
    protected static ?string $modelLabel = 'бронирование';
    protected static ?string $pluralModelLabel = 'бронирования';
    protected static ?string $navigationLabel = 'Бронирования';
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Логистика';
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Booking::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Данные рейса')
                    ->schema([
                        Forms\Components\Select::make('schedule_id')
                            ->label('Выберите рейс')
                            ->relationship('schedule', 'id')
                            ->searchable()
                            ->preload()
                            ->required()
                            // Используем атрибут full_path из модели Route
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                $date = $record->departure_at ? $record->departure_at->format('d.m.Y H:i') : '---';
                                $route = $record->route ? $record->route->full_path : "Маршрут не определен";
                                return "ID: {$record->id} | {$date} | {$route}";
                            })
                            ->getSearchResultsUsing(function (string $search): array {
                                return Schedule::query()
                                    ->with(['route.cities'])
                                    ->where('id', 'like', "%{$search}%")
                                    ->orWhereHas('route.cities', function ($query) use ($search) {
                                        $query->where('name', 'like', "%{$search}%");
                                    })
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($record) {
                                        $date = $record->departure_at ? $record->departure_at->format('d.m.Y H:i') : '---';
                                        $route = $record->route ? $record->route->full_path : "Маршрут не определен";
                                        return [$record->id => "ID: {$record->id} | {$date} | {$route}"];
                                    })
                                    ->toArray();
                            }),
                    ]),

                Forms\Components\Section::make('Данные пассажира')
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->label('Имя клиента')
                            ->required(),
                        Forms\Components\TextInput::make('client_phone')
                            ->label('Телефон')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('passengers_count')
                            ->label('Количество пассажиров')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Имя')
                    ->searchable(),

                Tables\Columns\TextColumn::make('client_phone')
                    ->label('Телефон')
                    ->searchable(),

                // ID Рейса берем из связи
                Tables\Columns\TextColumn::make('schedule.id')
                    ->label('ID рейса')
                    ->sortable(),

TextColumn::make('schedule.departure_at')
    ->label('Дата рейса')
    ->dateTime('d.m.Y H:i')
    ->sortable(),

                // Выводим полный путь маршрута в таблицу
                // app/Filament/Resources/BookingResource.php

                Tables\Columns\TextColumn::make('schedule.route.id') // Обращаемся к связи городов
                ->label('Маршрут')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(function ($state, $record) {
                        // Достаем коллекцию имен городов, отсортированную по порядку
                        $cities = $record->schedule?->route?->cities()
                            ->orderBy('city_route.order')
                            ->pluck('name');

                        if (!$cities || $cities->isEmpty()) {
                            return 'Не указан';
                        }

                        if ($cities->count() === 1) {
                            return $cities->first();
                        }

                        // Выводим только первый и последний
                        return $cities->first() . ' — ' . $cities->last();
                    })
                    // При наведении мышкой всё равно покажем полный путь для удобства
                    ->tooltip(fn ($record): string => $record->schedule?->route?->full_path ?? ''),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'confirmed' => 'Подтвержден',
                        'cancelled' => 'Отменен',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата заявки')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
