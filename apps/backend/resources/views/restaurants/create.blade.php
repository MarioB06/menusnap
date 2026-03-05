@extends('layouts.app')
@section('title', 'Restaurant erstellen')

@section('content')
<div style="min-height:calc(100vh - 10rem);display:flex;align-items:flex-start;justify-content:center;padding:2rem 1rem">
    <div class="cx-md">

        <h1 style="font-size:1.5rem;font-weight:700;margin:0 0 2rem">Neues Restaurant</h1>

        <form method="POST" action="{{ route('web.restaurants.store') }}" class="card" style="padding:1.5rem">
            @csrf

            <div style="margin-bottom:1.25rem">
                <label for="name" class="lbl">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="inp" placeholder="z.B. Pizzeria Bella">
                @error('name')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:1.25rem">
                <label for="description" class="lbl">Beschreibung</label>
                <textarea id="description" name="description" rows="3" class="inp" placeholder="Kurze Beschreibung deines Restaurants" style="resize:vertical">{{ old('description') }}</textarea>
                @error('description')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:1.25rem">
                <label for="address" class="lbl">Adresse</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" class="inp" placeholder="Musterstraße 1, 8000 Zürich">
                @error('address')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem">
                <div>
                    <label for="phone" class="lbl">Telefon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="inp" placeholder="+41 44 123 45 67">
                    @error('phone')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="website" class="lbl">Website</label>
                    <input type="url" id="website" name="website" value="{{ old('website') }}" class="inp" placeholder="https://example.com">
                    @error('website')<p class="err">{{ $message }}</p>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-p">Restaurant erstellen</button>
        </form>
    </div>
</div>
@endsection
