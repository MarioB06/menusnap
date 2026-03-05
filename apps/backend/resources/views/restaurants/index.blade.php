@extends('layouts.app')
@section('title', 'Meine Restaurants')

@section('content')
<div class="cx" style="padding-top:2rem;padding-bottom:2rem">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
        <h1 style="font-size:1.5rem;font-weight:700;margin:0">Meine Restaurants</h1>
        <a href="{{ route('web.restaurants.create') }}" class="btn btn-g" style="width:auto;padding:.5rem 1.25rem;font-size:.875rem">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Neues Restaurant
        </a>
    </div>

    @if($restaurants->isEmpty())
        <div class="card tc" style="padding:3rem 1.5rem">
            <svg style="width:48px;height:48px;color:var(--text-muted);margin:0 auto .75rem;display:block;opacity:.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <p style="font-weight:500;margin:0 0 .25rem;color:var(--text-sub)">Noch keine Restaurants</p>
            <p style="font-size:.875rem;color:var(--text-muted);margin:0 0 1.5rem">Erstelle dein erstes Restaurant, um loszulegen.</p>
            <a href="{{ route('web.restaurants.create') }}" class="btn btn-p" style="width:auto;display:inline-flex;padding:.5rem 1.25rem">Restaurant erstellen</a>
        </div>
    @else
        <div class="g2">
            @foreach($restaurants as $restaurant)
                <a href="{{ route('web.restaurants.show', $restaurant) }}" class="card card-h" style="display:block;padding:1.25rem;transition:all .15s">
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem">
                        @if($restaurant->logo_path)
                            <img src="{{ Storage::disk('public')->url($restaurant->logo_path) }}" alt="" style="width:40px;height:40px;border-radius:.5rem;object-fit:cover;flex-shrink:0">
                        @else
                            <div style="width:40px;height:40px;border-radius:.5rem;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg style="width:20px;height:20px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        @endif
                        <div style="min-width:0;flex:1">
                            <p style="font-weight:600;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $restaurant->name }}</p>
                            @if($restaurant->address)
                                <p style="font-size:.8125rem;color:var(--text-sub);margin:.125rem 0 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $restaurant->address }}</p>
                            @endif
                        </div>
                        <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:{{ $restaurant->is_active ? '#22c55e' : '#ef4444' }}"></span>
                    </div>
                    <div style="display:flex;gap:1rem;font-size:.8125rem;color:var(--text-muted)">
                        <span>{{ $restaurant->menus_count }} {{ $restaurant->menus_count === 1 ? 'Menü' : 'Menüs' }}</span>
                        <span>{{ $restaurant->tables_count }} {{ $restaurant->tables_count === 1 ? 'Tisch' : 'Tische' }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
