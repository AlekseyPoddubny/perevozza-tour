<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;

class ClosePastSchedules extends Command
{
    // Как мы будем вызывать команду вручную (для теста)
    protected $signature = 'schedules:close-past';

    protected $description = 'Переводит прошедшие рейсы в статус Завершен';

    public function handle()
    {
        // Находим все запланированные рейсы, время которых уже прошло
        $affectedRows = Schedule::where('status', 'scheduled')
            ->where('departure_at', '<=', Carbon::now())
            ->update(['status' => 'active']);

        $this->info("Обновлено рейсов: {$affectedRows}");
    }
}
