<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perevozza Tour — Поиск рейсов</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] },
                    colors: {
                        'gold': '#F59E0B',
                        'dark-bg': '#000000',
                        'card-bg': '#0A0A0A',
                    }
                }
            }
        }
    </script>

    <style>
        .gold-glow { text-shadow: 0 0 10px rgba(245, 158, 11, 0.4); }
        .btn-gold {
            background: linear-gradient(180deg, #FDE047 0%, #F59E0B 100%);
        }
        .btn-gold:hover { background: linear-gradient(180deg, #F59E0B 0%, #D97706 100%); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #F59E0B; border-radius: 5px; }
    </style>
</head>
<body class="bg-dark-bg text-white font-sans antialiased">

<nav x-data="{ mobileOpen: false, contactsOpen: false }" class="border-b border-zinc-900 bg-black/50 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
        <a href="/" class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="font-black text-2xl tracking-tighter uppercase">Perevozza <span class="text-gold">Tour</span></span>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-8 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500">
            <a href="{{ url('/') }}" class="hover:text-gold transition">Главная</a>
            <a href="{{ route('page.show', 'about') }}" class="{{ (isset($page) && $page->slug === 'about') ? 'text-gold' : '' }} hover:text-gold transition">О нас</a>
            <a href="{{ route('page.show', 'contacts') }}" class="{{ (isset($page) && $page->slug === 'contacts') ? 'text-gold' : '' }} hover:text-gold transition">Контакты</a>
        </div>

        <div class="flex items-center gap-3">

            <div class="relative">
                <button @click="contactsOpen = !contactsOpen; mobileOpen = false" class="text-gold font-bold text-[10px] uppercase border border-gold/30 px-5 py-2.5 rounded-full hover:bg-gold hover:text-black transition tracking-widest flex items-center gap-2">
                    Связаться
                    <svg class="w-3 h-3 transition-transform" :class="{'rotate-180': contactsOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="contactsOpen"
                     @click.away="contactsOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                /* Мобилка: фиксированно по центру. Десктоп: абсолютно под кнопкой */
                class="fixed inset-x-4 top-[15%] mx-auto md:absolute md:inset-x-auto md:top-full md:mt-3 md:right-0 w-auto max-w-[360px] md:w-80 bg-zinc-900 border border-gold/20 rounded-3xl shadow-2xl overflow-hidden z-[60]"
                style="display: none;">

                @forelse($headerContacts as $contact)
                    <div class="p-4 border-b border-white/5 last:border-0 hover:bg-white/[0.02] transition-colors">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    <p class="text-[9px] text-gold uppercase tracking-[0.15em] truncate">{{ $contact->title }}</p>
                                </div>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->subtitle) }}" class="text-sm text-white hover:text-gold transition-colors">{{ $contact->subtitle }}</a>
                            </div>
                            <div class="flex items-center gap-1">
                                @foreach($contact->links as $link)
                                    @php
                                        $type = $link->type;
                                        $hoverColor = match(true) {
                                            str_contains($type, 'telegram')  => 'hover:text-[#26A5E4] hover:bg-[#26A5E4]/15',
                                            str_contains($type, 'whatsapp')  => 'hover:text-[#25D366] hover:bg-[#25D366]/15',
                                            str_contains($type, 'viber')     => 'hover:text-[#7360F2] hover:bg-[#7360F2]/15',
                                            default => 'hover:text-gold hover:bg-gold/15'
                                        };
                                    @endphp
                                    <a href="{{ $link->url }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-zinc-500 transition-all {{ $hoverColor }}">
                                        <x-icon name="{{ $type }}" class="w-4 h-4" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-[10px] text-zinc-600 uppercase tracking-widest">Нет контактов</div>
                @endforelse
            </div>
        </div>

        <button @click="mobileOpen = !mobileOpen; contactsOpen = false" class="md:hidden p-2 text-zinc-400 hover:text-gold transition">
            <svg x-show="!mobileOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <svg x-show="mobileOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    </div>

    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         class="md:hidden bg-zinc-950 border-t border-white/5 p-6 space-y-4 shadow-2xl"
         style="display: none;">
        <a href="{{ url('/') }}" class="block text-xs font-bold uppercase tracking-[0.2em] text-zinc-400 hover:text-gold">Главная</a>
        <a href="{{ route('page.show', 'about') }}" class="block text-xs font-bold uppercase tracking-[0.2em] {{ (isset($page) && $page->slug === 'about') ? 'text-gold' : 'text-zinc-400' }} hover:text-gold">О нас</a>
        <a href="{{ route('page.show', 'contacts') }}" class="block text-xs font-bold uppercase tracking-[0.2em] {{ (isset($page) && $page->slug === 'contacts') ? 'text-gold' : 'text-zinc-400' }} hover:text-gold">Контакты</a>
    </div>

    <div x-show="contactsOpen" @click="contactsOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[55] md:hidden" style="display: none;"></div>
</nav>



<section class="relative bg-black pt-12 pb-24 flex flex-col items-center overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-60">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-gold/10 via-black to-black"></div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500 text-green-500 p-4 rounded-xl mb-6 text-center z-20">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-4xl w-full px-4 relative z-10 flex justify-center">
        <img src="{{ asset('images/hero-van.webp') }}" alt="Van" class="w-full h-auto max-h-[420px] object-contain ">
    </div>

    <div class="text-center mt-12 mb-12 space-y-5 px-4 z-10 relative">
        <h1 class="text-4xl md:text-5xl font-bold text-gold gold-glow uppercase tracking-tighter leading-tight">
            PEREVOZZA. Пассажирские перевозки.
        </h1>
        <p class="text-xl md:text-2xl font-semibold uppercase tracking-[0.25em] text-white">Уверенно. Регулярно.</p>
        <div class="flex items-center justify-center gap-5 text-base md:text-lg border-t border-gold/20 pt-7 mt-7 max-w-3xl mx-auto text-gray-300">
            <span class="text-gold text-3xl font-light">⇄</span>
            <span class="uppercase tracking-widest leading-relaxed">ЛДНР — ЕС, Украина. И обратно.</span>
        </div>
    </div>
</section>

<section class="relative -mt-10 z-20 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="bg-zinc-900 border border-gold/20 rounded-2xl shadow-2xl p-6 md:p-8">
            <form action="{{ route('search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gold uppercase ml-1">Откуда</label>
                    <select name="from" class="w-full bg-black border border-zinc-800 rounded-xl p-3 focus:border-gold outline-none transition text-sm text-white">
                        <option value="">Выберите город</option>
                        @foreach($citiesFrom as $id => $name)
                            <option value="{{ $id }}" {{ request('from') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gold uppercase ml-1">Куда</label>
                    <select name="to" class="w-full bg-black border border-zinc-800 rounded-xl p-3 focus:border-gold outline-none transition text-sm text-white">
                        <option value="">Выберите город</option>
                        @foreach($citiesTo as $id => $name)
                            <option value="{{ $id }}" {{ request('to') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gold uppercase ml-1">Дата</label>
                    <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="w-full bg-black border border-zinc-800 rounded-xl p-3 focus:border-gold outline-none transition text-sm text-white">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-grow btn-gold text-black font-extrabold py-3.5 rounded-xl text-lg uppercase tracking-widest transition transform hover:scale-[1.02]">
                        Найти
                    </button>
                    @if(request()->anyFilled(['from', 'to', 'date']))
                        <a href="{{ route('home') }}" class="bg-zinc-800 text-white px-5 py-3.5 rounded-xl flex items-center justify-center hover:bg-zinc-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

<main class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold uppercase tracking-widest text-center mb-12 text-gold">
        {{ request()->anyFilled(['from', 'to', 'date']) ? 'Результаты поиска' : 'Все ближайшие рейсы' }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($schedules as $schedule)
            <div x-data="{ showTooltip: false }" class="relative bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden hover:border-gold/50 transition-all group">
                <div class="p-6 relative">
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-gold/10 text-gold text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-tighter border border-gold/20">
                            {{ $schedule->type === 'regular' ? 'Регулярный' : 'Дополнительный' }}
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-white leading-none">{{ number_format($schedule->price_rub ?? 0, 0, '.', ' ') }} ₽</p>
                            <p class="text-[10px] text-gray-500 mt-1">{{ number_format($schedule->price_eur ?? 0, 0) }} €</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-[10px] text-gray-500 uppercase mb-3 tracking-widest">Маршрут рейса</p>

                        <div class="relative pl-4 border-l-2 border-dashed border-gold/30 space-y-4 ml-1">
                            @php
                                $cities = explode(' — ', $schedule->route->full_path);
                                $startCity = $cities[0];
                                $endCity = end($cities);
                                $middleCitiesCount = count($cities) - 2;
                            @endphp

                            <div class="relative">
                                <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 bg-gold rounded-full shadow-[0_0_10px_#F59E0B]"></div>
                                <p class="text-[10px] text-gray-500 uppercase leading-none mb-1">Отправление</p>
                                <p class="text-sm font-bold text-white uppercase tracking-tight">{{ $startCity }}</p>
                            </div>

                            @if($middleCitiesCount > 0)
                                <div class="py-1">
                                    <div @mouseenter="showTooltip = true"
                                         @mouseleave="showTooltip = false"
                                         @click="showTooltip = !showTooltip"
                                         @click.away="showTooltip = false"
                                         class="flex items-center gap-2 cursor-help w-fit group/info outline-none"
                                         tabindex="0">
                                <span class="text-[9px] text-gold/60 font-medium uppercase tracking-tighter">
                                    пункты сбора
                                </span>
                                        <svg class="w-3.5 h-3.5 text-gold/40 group-hover/info:text-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endif

                            <div class="relative">
                                <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 bg-zinc-900 border-2 border-gold rounded-full"></div>
                                <p class="text-[10px] text-gray-500 uppercase leading-none mb-1">Прибытие</p>
                                <p class="text-sm font-bold text-white uppercase tracking-tight">{{ $endCity }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-4 border-t border-zinc-800/50 mb-6">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase">Выезд</p>
                            <p class="text-[11px] font-semibold">
                                {{ $schedule->departure_at ? \Carbon\Carbon::parse($schedule->departure_at)->translatedFormat('d.m.Y H:i') : 'Уточняйте' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase">Авто</p>
                            <p class="text-[11px] font-semibold truncate">{{ $schedule->vehicle->make_model ?? 'Микроавтобус' }}</p>
                        </div>
                    </div>

                    <div x-show="showTooltip"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute bottom-[76px] left-6 right-6 z-50 pointer-events-none"
                         style="display: none;">
                        <div class="bg-black/95 border border-gold/30 rounded-xl p-4 shadow-2xl backdrop-blur-md">
                            <p class="text-gold/50 text-[8px] uppercase font-bold mb-3 tracking-widest border-b border-white/5 pb-2 text-center">
                                Пункты сбора
                            </p>
                            <div class="space-y-2">
                                {{-- Берем города напрямую из связи маршрута рейса --}}
                                @foreach($schedule->route->cities as $city)
                                    {{-- Проверяем новое условие: является ли город пунктом сбора --}}
                                    @if($city->is_main)
                                        <div class="flex items-center gap-2">
                                            <div class="w-1 h-1 rounded-full {{ $loop->first || $loop->last ? 'bg-gold' : 'bg-gold/30' }}"></div>
                                            <p class="text-[10px] font-bold uppercase {{ $loop->first || $loop->last ? 'text-gold' : 'text-zinc-300' }}">
                                                {{ $city->name }} {{-- Обязательно ->name, так как теперь это объект --}}
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button onclick="openModal({{ $schedule->id }}, '{{ $schedule->route->full_path }}', '{{ $startCity }}', '{{ $endCity }}')"
                            class="w-full bg-zinc-800 group-hover:bg-gold group-hover:text-black transition-all font-bold py-3.5 rounded-xl uppercase text-[10px] tracking-widest relative z-10">
                        Забронировать
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 border border-dashed border-zinc-800 rounded-3xl">
                <p class="text-gray-500 uppercase tracking-widest">Рейсов не найдено</p>
            </div>
        @endforelse
    </div>
</main>

<footer class="py-12 border-t border-zinc-900 text-center text-zinc-600 text-[10px] uppercase tracking-[0.3em]">
    &copy; {{ date('Y') }} Perevozza Tour
</footer>

<div id="bookingModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 w-full max-w-md rounded-2xl p-6 relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-zinc-500 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h2 class="text-xl font-bold text-white mb-1">Бронирование</h2>
        <p id="modalRouteInfo" class="text-gold text-[11px] uppercase font-bold mb-6 leading-tight"></p>

        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="schedule_id" id="modal_schedule_id">

            <input type="hidden" name="from_city" id="modal_from_city">
            <input type="hidden" name="to_city" id="modal_to_city">


            <div class="space-y-1">
                <label class="text-[10px] font-bold text-zinc-500 uppercase ml-1">Имя</label>
                <input type="text" name="client_name" required class="w-full bg-black border border-zinc-800 rounded-xl p-3 text-white focus:border-gold outline-none transition">
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-bold text-zinc-500 uppercase ml-1">Телефон</label>
                <input type="text" name="client_phone" id="phone_mask" required placeholder="+7 (___) ___-__-__" class="w-full bg-black border border-zinc-800 rounded-xl p-3 text-white focus:border-gold outline-none transition">
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-bold text-zinc-500 uppercase ml-1">Мест</label>
                <select name="passengers" class="w-full bg-black border border-zinc-800 rounded-xl p-3 text-white focus:border-gold outline-none transition">
                    @for($i = 1; $i <= 8; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                </select>
            </div>

            <button type="submit" class="w-full bg-gold text-black font-bold py-4 rounded-xl hover:bg-yellow-500 transition mt-4 shadow-lg shadow-gold/20 uppercase text-xs tracking-widest">
                Подтвердить
            </button>
        </form>
    </div>
</div>

<script>
    // Обновляем функцию, чтобы она принимала города
    function openModal(id, route, from, to) {
        document.getElementById('modal_schedule_id').value = id;
        document.getElementById('modalRouteInfo').innerText = route;
        // Записываем города в скрытые инпуты
        document.getElementById('modal_from_city').value = from;
        document.getElementById('modal_to_city').value = to;
        document.getElementById('bookingModal').classList.remove('hidden');
    }
</script>

<script src="https://unpkg.com/imask"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone_mask');
        if (phoneInput) {
            IMask(phoneInput, { mask: '+{7} (000) 000-00-00' });
        }
    });
</script>

</body>
</html>
