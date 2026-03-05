@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div style="min-height:calc(100vh - 10rem);display:flex;align-items:center;justify-content:center;padding:2rem 1rem">
    <div style="text-align:center;max-width:24rem">
        <div class="ib ib-g">
            <svg style="width:32px;height:32px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <h1 style="font-size:1.5rem;font-weight:700;margin:0 0 .5rem">Willkommen bei MenuSnap</h1>
        <p style="color:var(--text-sub);margin:0 0 2rem;font-size:.9375rem">Erstelle dein Restaurant, um Speisekarten und Tische zu verwalten.</p>
        <a href="{{ route('web.restaurants.create') }}" class="btn btn-g" style="display:inline-flex;padding:.75rem 2rem">
            <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Restaurant erstellen
        </a>
    </div>
</div>
@endsection
