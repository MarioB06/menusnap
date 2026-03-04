@if ($menu->categories->isNotEmpty())
<div>
    @if ($restaurant->menus->count() > 1)
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-indigo-600 rounded-full"></span>
            {{ $menu->name }}
        </h2>
    @endif

    <div class="space-y-6">
        @foreach ($menu->categories as $category)
            @include('guest.partials.category-section', ['category' => $category])
        @endforeach
    </div>
</div>
@endif
