<?php

namespace App\Filament\Resources;

use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ScheduleResource\Pages;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $modelLabel = 'рейс';
    protected static ?string $pluralModelLabel = 'расписание';
    protected static ?string $navigationGroup = 'Логистика';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Маршрут и статус')
                    ->schema([
                        Forms\Components\Select::make('route_id')
                            ->label('Маршрут')
                            ->relationship('route', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_path)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Select::make('status')
                            ->label('Статус выполнения')
                            ->options([
                                'scheduled' => 'Запланирован',
                                'active' => 'В пути',
                                'completed' => 'Завершен',
                                'cancelled' => 'Отменен',
                            ])
                            ->default('scheduled')
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Стоимость билета')
                    ->description('Укажите стоимость в обеих валютах')
                    ->schema([
                        Forms\Components\TextInput::make('price_rub')
                            ->label('Цена (RUB)')
                            ->numeric()
                            ->prefix('₽')
                            ->placeholder('0.00'),

                        Forms\Components\TextInput::make('price_eur')
                            ->label('Цена (EUR)')
                            ->numeric()
                            ->prefix('€')
                            ->placeholder('0.00'),
                    ])->columns(2),

                Forms\Components\Section::make('Тип рейса и время')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Тип рейса')
                            ->options([
                                'additional' => 'Дополнительный (разовый)',
                                'regular' => 'Регулярный',
                            ])
                            ->default('additional')
                            ->live()
                            ->required(),

                        Forms\Components\DateTimePicker::make('departure_at')
                            ->label('Дата и время выезда')
                            ->visible(fn (Get $get) => $get('type') === 'additional')
                            ->required(fn (Get $get) => $get('type') === 'additional'),

                        Forms\Components\Select::make('frequency')
                            ->label('Периодичность')
                            ->options([
                                'daily' => 'Ежедневно',
                                'weekdays' => 'По будням',
                                'weekly' => 'Раз в неделю',
                                'custom' => 'Несколько раз в неделю',
                            ])
                            ->visible(fn (Get $get) => $get('type') === 'regular')
                            ->required(fn (Get $get) => $get('type') === 'regular'),
                    ])->columns(2),

                Forms\Components\Section::make('Ресурсы')
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->label('Автомобиль')
                            ->relationship('vehicle', 'make_model')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('driver_id')
                            ->label('Водитель')
                            ->relationship('driver', 'full_name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->fontFamily('mono') // Моноширинный шрифт для ID
                    ->toggledHiddenByDefault(false)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('route.full_path')
                    ->label('Маршрут')
                    ->limit(30)
                    ->searchable() // Поиск по городам в маршруте
                    ->sortable(),

                Tables\Columns\TextColumn::make('departure_info')
                    ->label('Когда')
                    ->state(function (Schedule $record): string {
                        if ($record->type === 'regular') {
                            return match($record->frequency) {
                                'daily' => 'Ежедневно',
                                'weekdays' => 'По будням',
                                'weekly' => 'Раз в неделю',
                                'custom' => 'Неск. раз в неделю',
                                default => 'Регулярно',
                            };
                        }
                        return $record->departure_at?->format('d.m.Y H:i') ?? '—';
                    })
                    ->badge()
                    ->color(fn (Schedule $record): string => $record->type === 'regular' ? 'info' : 'gray')
                    ->sortable(['departure_at']), // Сортировка по реальной дате

                // Вывод цены в рублях
                Tables\Columns\TextColumn::make('price_rub')
                    ->label('Цена (₽)')
                    ->money('RUB', divideBy: 1)
                    ->sortable()
                    ->searchable(),

                // Вывод цены в евро
                Tables\Columns\TextColumn::make('price_eur')
                    ->label('Цена (€)')
                    ->money('EUR', divideBy: 1)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'gray',
                        'active' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'scheduled' => 'Запланирован',
                        'active' => 'В пути',
                        'completed' => 'Завершен',
                        'cancelled' => 'Отменен',
                    }),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип рейса')
                    ->options([
                        'regular' => 'Регулярный',
                        'additional' => 'Дополнительный',
                    ]),
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
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
