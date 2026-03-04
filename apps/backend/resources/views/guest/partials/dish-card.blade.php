<div class="flex gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-colors">
    @if ($dish->image_path)
        <img src="{{ asset('storage/' . $dish->image_path) }}"
             alt="{{ $dish->name }}"
             class="w-16 h-16 rounded-lg object-cover shrink-0">
    @endif

    <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-2">
            <h4 class="font-semibold text-gray-900 text-sm leading-tight">{{ $dish->name }}</h4>
            <span class="text-indigo-600 font-bold text-sm whitespace-nowrap">CHF {{ number_format($dish->price, 2) }}</span>
        </div>

        @if ($dish->description)
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $dish->description }}</p>
        @endif

        @if (!empty($dish->dietary_tags) || !empty($dish->allergens))
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach ($dish->dietary_tags ?? [] as $tag)
                    <span class="inline-block bg-emerald-100 text-emerald-700 text-[10px] font-medium px-1.5 py-0.5 rounded-full">{{ $tag }}</span>
                @endforeach
                @foreach ($dish->allergens ?? [] as $allergen)
                    <span class="inline-block bg-amber-100 text-amber-700 text-[10px] font-medium px-1.5 py-0.5 rounded-full">{{ $allergen }}</span>
                @endforeach
            </div>
        @endif
    </div>
</div>
