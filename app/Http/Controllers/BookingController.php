<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    /*

    public function store(Request $request)
    {

        $messages = [
            'client_name.required' => 'Пожалуйста, введите ваше имя.',
            'client_phone.required' => 'Номер телефона обязателен.',
            'client_phone.min' => 'Номер телефона слишком короткий.',
            'passengers.required' => 'Выберите количество мест.',
            'passengers.min' => 'Нужно забронировать хотя бы одно место.',
            'passengers.max' => 'За один раз можно забронировать не более 8 мест.',
        ];

        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'client_name' => 'required|string|min:2|max:100',
            'client_phone' => 'required|string|min:10', // Минимум для номера
            'passengers' => 'required|integer|min:1|max:8',
        ], $messages);

        // Если валидация прошла успешно, создаем запись
        $booking = \App\Models\Booking::create([
            'schedule_id' => $validated['schedule_id'],
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'],
            'passengers_count' => $validated['passengers'],
        ]);




        /*
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string',
            'passengers' => 'required|integer|min:1',
        ]);

        // Создаем запись
        $booking = Booking::create([
            'schedule_id' => $validated['schedule_id'],
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'],
            'passengers_count' => $validated['passengers'],
        ]);



        // Загружаем данные рейса, чтобы составить текст (важно для уведомления)
        $booking->load('schedule.route');

        // Форматируем дату для Telegram
        $date = \Carbon\Carbon::parse($booking->schedule->departure_at)->format('d.m.Y H:i');

        $text = "🆕 *Новая бронь!*\n\n" .
            "📍 *Откуда:* {$booking->schedule->route->departure_city}\n" .
            "🏁 *Куда:* {$booking->schedule->route->arrival_city}\n" .
            "📅 *Дата:* {$date}\n" .
            "👤 *Клиент:* {$booking->client_name}\n" .
            "📞 *Телефон:* {$booking->client_phone}\n" .
            "👥 *Мест:* {$booking->passengers_count}";

        // Отправка в Telegram с защитой от сбоев
        try {
            Http::timeout(5)->post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id' => env('TELEGRAM_ADMIN_CHAT_ID'),
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            // В лог можно записать ошибку, если уведомление не ушло
            \Log::error("Telegram booking alert failed: " . $e->getMessage());
        }

        return back()->with('success', 'Спасибо! Мы свяжемся с вами в ближайшее время.');
    }

    */

    public function store(Request $request)
    {
        // Сообщения об ошибках для валидации
        $messages = [
            'client_name.required' => 'Пожалуйста, введите ваше имя.',
            'client_phone.required' => 'Номер телефона обязателен.',
            'client_phone.min' => 'Номер телефона слишком короткий.',
            'passengers.required' => 'Выберите количество мест.',
            'passengers.min' => 'Нужно забронировать хотя бы одно место.',
            'passengers.max' => 'За один раз можно забронировать не более 8 мест.',
            'from_city.required' => 'Город отправления не определен.',
            'to_city.required' => 'Город прибытия не определен.',
        ];

        // Валидация входящих данных (включая города из скрытых полей)
        $validated = $request->validate([
            'schedule_id'  => 'required|exists:schedules,id',
            'client_name'   => 'required|string|min:2|max:100',
            'client_phone'  => 'required|string|min:10',
            'passengers'    => 'required|integer|min:1|max:8',
            'from_city'     => 'required|string',
            'to_city'       => 'required|string',
        ], $messages);

        // 1. Создаем запись в базе данных
        $booking = \App\Models\Booking::create([
            'schedule_id'      => $validated['schedule_id'],
            'client_name'      => $validated['client_name'],
            'client_phone'     => $validated['client_phone'],
            'passengers_count' => $validated['passengers'],
        ]);

        // 2. Получаем данные о рейсе для даты (через связь или напрямую)
        $schedule = \App\Models\Schedule::find($validated['schedule_id']);

        // Форматируем дату. Если рейс регулярный, можно добавить пометку
        $dateInfo = $schedule->departure_at
            ? \Carbon\Carbon::parse($schedule->departure_at)->translatedFormat('d.m.Y H:i')
            : ($schedule->type === 'regular' ? 'Регулярный рейс' : 'Уточнить у оператора');

        // 3. Формируем текст сообщения для Telegram
        $text = "🆕 *НОВАЯ БРОНЬ*\n\n" .
            "📍 *ОТКУДА:* {$validated['from_city']}\n" .
            "🏁 *КУДА:* {$validated['to_city']}\n" .
            "📅 *ДАТА:* {$dateInfo}\n\n" .
            "👤 *КЛИЕНТ:* {$validated['client_name']}\n" .
            "📞 *ТЕЛЕФОН:* `{$validated['client_phone']}`\n" .
            "👥 *МЕСТ:* {$validated['passengers']}\n\n" .
            "💳 *ID РЕЙСА:* #{$validated['schedule_id']}";

        // 4. Отправка в Telegram
        try {
            \Illuminate\Support\Facades\Http::timeout(5)->post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id'    => env('TELEGRAM_ADMIN_CHAT_ID'),
                'text'       => $text,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            \Log::error("Ошибка отправки в Telegram: " . $e->getMessage());
        }

        return back()->with('success', 'Спасибо! Ваш запрос принят, мы свяжемся с вами в ближайшее время.');
    }

}
