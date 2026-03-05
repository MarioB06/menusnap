@extends('layouts.app')
@section('title', $restaurant->name . ' bearbeiten')

@section('content')
<div style="min-height:calc(100vh - 10rem);display:flex;align-items:flex-start;justify-content:center;padding:2rem 1rem">
    <div class="cx-md">

        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:2rem">
            <a href="{{ route('web.restaurants.show', $restaurant) }}" style="padding:.5rem;color:var(--text-muted);display:flex;transition:color .15s" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">
                <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 style="font-size:1.5rem;font-weight:700;margin:0">Einstellungen</h1>
        </div>

        <form method="POST" action="{{ route('web.restaurants.update', $restaurant) }}" class="card" style="padding:1.5rem">
            @csrf @method('PUT')

            <div style="margin-bottom:1.25rem">
                <label for="name" class="lbl">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $restaurant->name) }}" required class="inp">
                @error('name')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:1.25rem">
                <label for="description" class="lbl">Beschreibung</label>
                <textarea id="description" name="description" rows="3" class="inp" style="resize:vertical">{{ old('description', $restaurant->description) }}</textarea>
                @error('description')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:1.25rem">
                <label for="address" class="lbl">Adresse</label>
                <input type="text" id="address" name="address" value="{{ old('address', $restaurant->address) }}" class="inp">
                @error('address')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem">
                <div>
                    <label for="phone" class="lbl">Telefon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $restaurant->phone) }}" class="inp">
                    @error('phone')<p class="err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="website" class="lbl">Website</label>
                    <input type="url" id="website" name="website" value="{{ old('website', $restaurant->website) }}" class="inp">
                    @error('website')<p class="err">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $restaurant->is_active) ? 'checked' : '' }} style="accent-color:var(--primary)">
                <label for="is_active" style="font-size:.875rem;color:var(--text-sub);margin:0">Restaurant ist aktiv und sichtbar</label>
            </div>

            <button type="submit" class="btn btn-p">Änderungen speichern</button>
        </form>

        <div x-data="{confirm:false}" style="margin-top:2rem;padding-top:2rem;border-top:1px solid var(--border)">
            <button type="button" @click="confirm=true" style="font-size:.8125rem;color:var(--text-muted);background:0;border:0;cursor:pointer;padding:0;transition:color .15s" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'">Restaurant löschen</button>
            <template x-teleport="body">
                <div x-show="confirm" x-cloak x-transition.opacity style="position:fixed;top:0;left:0;right:0;bottom:0;z-index:9999;display:flex;align-items:center;justify-content:center">
                    <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5)" @click="confirm=false"></div>
                    <form method="POST" action="{{ route('web.restaurants.destroy', $restaurant) }}" style="position:relative;background:var(--bg-card);border-radius:1rem;padding:1.5rem;width:100%;max-width:24rem;margin:0 1rem;box-shadow:0 25px 50px rgba(0,0,0,.25)">
                        @csrf @method('DELETE')
                        <h3 style="font-size:1.125rem;font-weight:700;margin:0 0 .5rem">Restaurant löschen?</h3>
                        <p style="color:var(--text-sub);font-size:.875rem;margin:0 0 1.25rem">Alle Menüs, Kategorien, Gerichte und Tische werden unwiderruflich gelöscht.</p>
                        <div style="display:flex;gap:.75rem">
                            <button type="button" @click="confirm=false" class="btn btn-o" style="flex:1">Abbrechen</button>
                            <button type="submit" style="flex:1;padding:.625rem;border-radius:.5rem;background:var(--danger);color:#fff;font-weight:600;border:0;cursor:pointer;font-size:.9375rem">Löschen</button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection
