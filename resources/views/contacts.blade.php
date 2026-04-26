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
                                <div class="bg-zinc-900/50 border border-zinc-800 rounded-[2rem] overflow-hidden hover:border-gold/30 transition duration-500 group">

                                    <div class="h-56 bg-black">
                                        @if($contact->photo)
                                            <img
                                                src="{{ asset('storage/' . $contact->photo) }}"
                                                alt="{{ $contact->title }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-zinc-950">
                    <span class="text-6xl font-black text-gold/40">
                        {{ mb_substr($contact->title, 0, 1) }}
                    </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-6 text-center">
                                        <h3 class="text-white font-black text-xl uppercase tracking-tighter group-hover:text-gold transition">
                                            {{ $contact->title }}
                                        </h3>

                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->subtitle) }}"
                                           class="inline-block mt-2 text-zinc-400 hover:text-gold transition-colors whitespace-nowrap text-sm font-semibold">
                                            {{ $contact->subtitle }}
                                        </a>

                                        @if($contact->links->count())
                                            <div class="flex justify-center gap-3 mt-5">
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
                                                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-white/5 text-zinc-500 transition-all duration-300 {{ $hoverColor }} hover:-translate-y-0.5">
                                                        <x-icon name="{{ $type }}" class="w-4 h-4" />
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
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
