<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты — Perevozza Tour</title>
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
<nav class="border-b border-zinc-900 bg-black/50 backdrop-blur-md sticky top-0 z-50">
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

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="text-gold font-bold text-[10px] uppercase border border-gold/30 px-5 py-2.5 rounded-full hover:bg-gold hover:text-black transition tracking-widest flex items-center gap-2">
                Связаться
                <svg class="w-3 h-3 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-80 bg-zinc-900 border border-gold/20 rounded-2xl shadow-2xl overflow-hidden z-50" style="display: none;">

                @forelse($headerContacts as $contact)
                    <div class="p-4 border-b border-white/5 last:border-0 hover:bg-white/[0.02] transition-colors">
                        <div class="flex items-center justify-between gap-4">

                            {{-- Левая часть: Данные --}}
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    <p class="text-[9px] text-gold uppercase tracking-[0.15em] truncate">
                                        {{ $contact->title }}
                                    </p>
                                </div>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->subtitle) }}"
                                   class="text-sm text-white hover:text-gold transition-colors whitespace-nowrap">
                                    {{ $contact->subtitle }}
                                </a>
                            </div>

                            {{-- Правая часть: Мессенджеры --}}
                            <div class="flex items-center gap-1">
                                @foreach($contact->links as $link)
                                    @php
                                        $type = $link->type;
                                        $hoverColor = match(true) {
                                            str_contains($type, 'telegram')  => 'hover:text-[#26A5E4] hover:bg-[#26A5E4]/15',
                                            str_contains($type, 'whatsapp')  => 'hover:text-[#25D366] hover:bg-[#25D366]/15',
                                            str_contains($type, 'viber')     => 'hover:text-[#7360F2] hover:bg-[#7360F2]/15',
                                            str_contains($type, 'imo')       => 'hover:text-[#00a3ff] hover:bg-[#00a3ff]/15',
                                            str_contains($type, 'max')       => 'hover:text-gold hover:bg-gold/15',
                                            default => 'hover:text-gold hover:bg-gold/15'
                                        };
                                    @endphp
                                    <a href="{{ $link->url }}"
                                       target="_blank"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-zinc-500 transition-all duration-300 {{ $hoverColor }} hover:-translate-y-0.5">
                                        <x-icon name="{{ $type }}" class="w-4 h-4" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <p class="text-[10px] text-zinc-600 uppercase tracking-widest">Нет доступных контактов</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
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

        @if(isset($contacts))
            <div class="space-y-20">
                @php
                    $personal = $contacts->where('category', 'personal');
                    $groups = $contacts->where('category', 'group');
                @endphp

                @if($personal->count())



                    <section class="pt-6  px-6">
                    <div class="max-w-5xl mx-auto text-center">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($personal as $contact)
                                <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-[2rem] flex flex-col md:flex-row justify-between items-center gap-6 hover:border-gold/30 transition duration-500 group">
                                    <div class="text-center md:text-left">
                                        <h3 class="text-white font-bold text-xl uppercase group-hover:text-gold transition">{{ $contact->title }}</h3>
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->subtitle) }}"
                                           class="text-white hover:text-gold transition-colors whitespace-nowrap">
                                            {{ $contact->subtitle }}
                                        </a>
                                    </div>
                                    <div class="flex gap-3">
                                        @foreach($contact->links as $link)
                                            @php
                                                $type = $link->type;
                                                $hoverColor = match(true) {
                                                    str_contains($type, 'telegram')  => 'hover:text-[#26A5E4] hover:bg-[#26A5E4]/15',
                                                    str_contains($type, 'whatsapp')  => 'hover:text-[#25D366] hover:bg-[#25D366]/15',
                                                    str_contains($type, 'viber')     => 'hover:text-[#7360F2] hover:bg-[#7360F2]/15',
                                                    str_contains($type, 'imo')       => 'hover:text-[#00a3ff] hover:bg-[#00a3ff]/15',
                                                    str_contains($type, 'max')       => 'hover:text-gold hover:bg-gold/15',
                                                    default => 'hover:text-gold hover:bg-gold/15'
                                                };
                                            @endphp
                                            <a href="{{ $link->url }}"
                                               target="_blank"
                                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-zinc-500 transition-all duration-300 {{ $hoverColor }} hover:-translate-y-0.5">
                                                <x-icon name="{{ $type }}" class="w-4 h-4" />
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

            </section>
                @endif

                @if($groups->count())


                    <section class=" pb-12 px-6">
                        <div class="max-w-5xl mx-auto text-center">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($groups as $group)
                            @foreach($group->links as $link)



                                    <a href="{{ $link->url }}" target="_blank" >
                                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-[2rem] flex flex-col md:flex-row justify-between items-center text-center gap-6 hover:border-gold/30 transition duration-500 group">
                                            <div class="text-center md:text-center">
                                            <h3 class="text-white text-center font-bold text-xl uppercase group-hover:text-gold transition">{{ $group->title }}</h3>
                                            </div>
                                        </div>
                                    </a>



                            @endforeach
                        @endforeach
                            </div>
                        </div>

                    </section>



                @endif
            </div>
        @endif
    </div>



</main>

<footer class="border-t border-zinc-900 py-12 text-center text-zinc-600 text-[10px] uppercase tracking-[0.3em] font-bold">
    &copy; {{ date('Y') }} Perevozza Tour
</footer>

</body>
</html>
