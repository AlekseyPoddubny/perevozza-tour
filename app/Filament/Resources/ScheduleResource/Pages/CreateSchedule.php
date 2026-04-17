<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Если это регулярный рейс и у нас есть массив дат
        if (isset($data['type']) && $data['type'] === 'regular' && !empty($data['dates_repeater'])) {

            $multiDates = $data['dates_repeater'];
            $lastRecord = null;

            // Удаляем этот ключ, чтобы Eloquent не ругался при создании записи
            unset($data['dates_repeater']);

            foreach ($multiDates as $dateItem) {
                if (empty($dateItem['departure_at'])) continue;

                // Создаем записи по очереди
                $lastRecord = static::getModel()::create(array_merge($data, [
                    'departure_at' => $dateItem['departure_at'],
                ]));
            }

            // Возвращаем последнюю созданную запись
            return $lastRecord;
        }

        // Если это разовый рейс — создаем как обычно
        return static::getModel()::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
