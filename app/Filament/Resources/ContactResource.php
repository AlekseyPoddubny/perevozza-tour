<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationLabel = 'Контакты и Группы';
    protected static ?string $navigationGroup = 'Управление';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Основное')->schema([
                Forms\Components\TextInput::make('title')->required()->label('Заголовок'),
                Forms\Components\TextInput::make('subtitle')->label('Подзаголовок/Номер'),
                Forms\Components\Select::make('category')
                    ->options(['personal' => 'Контакт', 'group' => 'Группа/Сообщество'])
                    ->required()->label('Тип'),
                Forms\Components\FileUpload::make('photo')
                    ->label('Фото')
                    ->image()
                    ->directory('contacts')
                    ->disk('public')
                    ->visibility('public')
                    ->imageEditor(),
                Forms\Components\Toggle::make('is_active')->label('Активен')->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Кнопки мессенджеров')->schema([
                Forms\Components\Repeater::make('links')
                    ->relationship('links')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Мессенджер / Соцсеть')
                            ->searchable()
                            ->options([
                                'Популярные мессенджеры' => [
                                    'fab-telegram'   => 'Telegram',
                                    'fab-whatsapp'   => 'WhatsApp',
                                    'fab-viber'      => 'Viber',
                                    'custom-max'     => 'Max (Перевозки)',
                                    'custom-imo'     => 'IMO',
                                    'fab-signal'     => 'Signal',
                                ],
                                'Социальные сети' => [
                                    'fab-vk'            => 'ВКонтакте',
                                    'fab-odnoklassniki' => 'Одноклассники',
                                    'fab-instagram'     => 'Instagram*',
                                    'fab-facebook'      => 'Facebook*',
                                    'fab-tiktok'        => 'TikTok',
                                    'fab-youtube'       => 'YouTube',
                                    'fab-threads'       => 'Threads*',
                                ],
                                'Связь и прочее' => [
                                    'fas-phone'      => 'Телефон (звонок)',
                                    'fas-envelope'   => 'Email',
                                    'fab-skype'      => 'Skype',
                                    'fab-discord'    => 'Discord',
                                    'fab-snapchat'   => 'Snapchat',
                                    'fab-wechat'     => 'WeChat',
                                ]
                            ])
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('url')->required()->label('Ссылка (https://...)'),
                    ])->columns(2)->createItemButtonLabel('Добавить кнопку')
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->label('Название')->searchable(),
            Tables\Columns\TextColumn::make('subtitle')->label('Инфо'),
            Tables\Columns\BadgeColumn::make('category')
                ->label('Категория')
                ->colors([
                    'success' => 'personal',
                    'warning' => 'group',
                ]),
            Tables\Columns\TextColumn::make('links_count')->counts('links')->label('Кнопок'),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label('Статус'),
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
