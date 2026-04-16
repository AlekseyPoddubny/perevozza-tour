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

<main class="pb-24">
    <section class="pt-24 pb-12 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <p class="text-xl md:text-2xl font-semibold uppercase tracking-[0.25em] text-white">{{ $page->title }}</p>
            <div class="h-1 w-20 bg-gold mx-auto"></div>
        </div>
    </section>

    <section class="px-6 mb-20">
        <div class="max-w-4xl mx-auto bg-card-bg border border-zinc-900 rounded-[2rem] p-8 md:p-12 shadow-2xl">
            <div class="page-content">
                {!! $page->content !!}
            </div>
        </div>
    </section>


    @if($page->slug === 'about')
        <section class="pt-6 pb-12 px-6">
            <div class="max-w-5xl mx-auto text-center">
                <div class="max-w-5xl mx-auto text-center pb-12 px-6 ">
                    <p class="text-xl md:text-2xl font-semibold uppercase tracking-[0.25em] text-white">Наш автопарк</p>
                    <div class="h-1 w-20 bg-gold mx-auto"></div>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($vehicles as $vehicle)
                        <div class="bg-zinc-900/50 border border-zinc-800 rounded-[2rem] overflow-hidden">
                            <div class="h-48 bg-black">
                                @if($vehicle->image)
                                    <img src="{{ asset('storage/' . $vehicle->image) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-xl font-black uppercase tracking-tighter">{{ $vehicle->make_model }}</h3>
                                    <span class="text-gold text-[10px] font-bold border border-gold/20 px-2 py-0.5 rounded">{{ $vehicle->seats }} мест</span>
                                </div>
                                <p class="text-zinc-500 text-xs">{{ $vehicle->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="mt-20">
                    <div class="max-w-5xl mx-auto text-center pb-12 px-6 ">
                        <p class="text-xl md:text-2xl font-semibold uppercase tracking-[0.25em] text-white">Отзывы клиентов</p>
                        <div class="h-1 w-20 bg-gold mx-auto"></div>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                             @foreach($reviews as $review)
                                <div class="bg-black border border-zinc-800 p-8 rounded-[2rem] ">
                                    <div class="flex gap-1 text-gold mb-4">
                                        @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                                    </div>
                                <p class="text-zinc-300 mb-6">"{{ $review->content }}"</p>
                                <span class="text-gold font-bold uppercase text-[10px] tracking-widest">— {{ $review->author_name }}</span>
                                </div>
                            @endforeach
                        </div>
                </div>
            </div>
        </section>
    @endif

</main>

<footer class="py-12 border-t border-zinc-900 text-center text-zinc-600 text-[10px] uppercase tracking-[0.3em]">
    &copy; {{ date('Y') }} Perevozza Tour
</footer>

</body>
</html>
