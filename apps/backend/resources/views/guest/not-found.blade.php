@extends('layouts.guest')

@section('title', 'Nicht gefunden')

@section('content')
    <div class="max-w-lg mx-auto px-4 py-24 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-3xl mx-auto mb-6">
            🍽️
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Speisekarte nicht gefunden</h1>
        <p class="text-gray-500 mb-8">
            Der QR-Code scheint ungültig zu sein oder das Restaurant ist momentan nicht verfügbar.
        </p>
        <a href="{{ url('/') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-6 py-3 rounded-xl transition-colors">
            Zur Startseite
        </a>
    </div>
@endsection
