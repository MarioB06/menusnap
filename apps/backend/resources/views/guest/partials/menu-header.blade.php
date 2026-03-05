<header class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-indigo-800 to-violet-700 -z-10"></div>
    <div class="absolute -top-20 -left-20 w-60 h-60 rounded-full bg-indigo-500/20 blur-3xl -z-10"></div>
    <div class="absolute -bottom-20 -right-10 w-72 h-72 rounded-full bg-violet-500/20 blur-3xl -z-10"></div>

    <div class="max-w-lg mx-auto px-4 py-8 text-center">
        @if ($restaurant->logo_path)
            <img src="{{ asset('storage/' . $restaurant->logo_path) }}"
                 alt="{{ $restaurant->name }}"
                 class="w-16 h-16 rounded-2xl object-cover mx-auto mb-4 shadow-lg ring-2 ring-white/20">
        @endif

        <h1 class="text-2xl font-bold text-white tracking-tight">{{ $restaurant->name }}</h1>

        @if ($restaurant->description)
            <p class="text-white/60 text-sm mt-2 max-w-xs mx-auto">{{ $restaurant->description }}</p>
        @endif

        @if($table)
        <div class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 text-white/70 text-xs font-medium px-3 py-1 rounded-full mt-4 backdrop-blur-sm">
            {{ $table->label }}
        </div>
        @endif
    </div>
</header>
