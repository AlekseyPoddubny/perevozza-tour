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

@include('components.header')

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


                <div class="max-w-5xl mx-auto text-center pb-12 px-6 mt-24">
                    <p class="text-xl md:text-2xl font-semibold uppercase tracking-[0.25em] text-white">
                        Наши водители
                    </p>
                    <div class="h-1 w-20 bg-gold mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($drivers as $driver)
                        <div class="bg-zinc-900/50 border border-zinc-800 rounded-[2rem] overflow-hidden">
                            <div class="h-48 bg-black">
                                @if($driver->photo)
                                    <img
                                        src="{{ asset('storage/' . $driver->photo) }}"
                                        alt="{{ $driver->full_name }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-zinc-950">
                        <span class="text-5xl font-black text-gold/40">
                            {{ mb_substr($driver->full_name, 0, 1) }}
                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-black uppercase tracking-tighter">
                                    {{ $driver->full_name }}
                                </h3>

                                @if($driver->additional_info)
                                    <p class="text-zinc-500 text-xs mt-2">
                                        {{ $driver->additional_info }}
                                    </p>
                                @endif
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
