@if ($category->dishes->isNotEmpty())
<div>
    <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wider mb-3 pl-1">{{ $category->name }}</h3>
    @if ($category->description)
        <p class="text-xs text-gray-400 mb-3 pl-1">{{ $category->description }}</p>
    @endif

    <div class="space-y-2">
        @foreach ($category->dishes as $dish)
            @include('guest.partials.dish-card', ['dish' => $dish])
        @endforeach
    </div>
</div>
@endif
