@extends('layouts.guest')

@section('title', $restaurant->name)

@section('content')
    {{-- Header --}}
    @include('guest.partials.menu-header', ['restaurant' => $restaurant, 'table' => $table])

    {{-- Menu Content --}}
    <div class="max-w-lg mx-auto px-4 py-6 space-y-8">
        @forelse ($restaurant->menus as $menu)
            @include('guest.partials.menu-section', ['menu' => $menu])
        @empty
            <div class="text-center py-12">
                <p class="text-gray-400 text-lg">Noch keine Speisekarte verfügbar.</p>
            </div>
        @endforelse
    </div>
@endsection
