@extends('layouts.app')
@section('title', 'Einstellungen')

@section('content')
<div class="cx" style="max-width:32rem;padding-top:2rem;padding-bottom:2rem">

    <h1 style="font-size:1.5rem;font-weight:700;margin:0 0 1.5rem">Einstellungen</h1>

    {{-- User Info --}}
    <div class="card" style="padding:1.25rem;margin-bottom:1.5rem">
        <div style="display:flex;align-items:center;gap:1rem">
            <div class="av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div style="min-width:0">
                <p style="font-weight:600;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $user->name }}</p>
                <p style="font-size:.875rem;color:var(--text-sub);margin:.125rem 0 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    {{-- General --}}
    <div class="card" style="margin-bottom:1.5rem">
        <div style="padding:1.25rem;border-bottom:1px solid var(--border)">
            <p style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1rem">Allgemein</p>
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem">
                <div>
                    <p style="font-weight:500;margin:0">Erscheinungsbild</p>
                    <p style="font-size:.8125rem;color:var(--text-sub);margin:.25rem 0 0">Wähle dein Theme</p>
                </div>
                <div class="tt" x-data="{ tm: null }" x-init="tm = Alpine.$data(document.body)">
                    <button @click="tm.setMode('system')" :class="tm && tm.mode === 'system' ? 'on' : ''">System</button>
                    <button @click="tm.setMode('light')" :class="tm && tm.mode === 'light' ? 'on' : ''">Hell</button>
                    <button @click="tm.setMode('dark')" :class="tm && tm.mode === 'dark' ? 'on' : ''">Dunkel</button>
                </div>
            </div>
        </div>
        <div style="padding:1.25rem">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <p style="font-weight:500;margin:0">Version</p>
                <p style="font-size:.875rem;color:var(--text-sub);margin:0">Web 1.0.0</p>
            </div>
        </div>
    </div>

    {{-- Logout --}}
    <div x-data="{confirm:false}">
        <button @click="confirm=true" class="btn btn-d">Abmelden</button>

        <div x-show="confirm" x-transition style="position:fixed;inset:0;z-index:50">
            <div style="position:absolute;inset:0;background:rgba(0,0,0,.5)" @click="confirm=false"></div>
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none">
            <div style="position:relative;background:var(--bg-card);border-radius:1rem;padding:1.5rem;max-width:24rem;margin:0 1rem;box-shadow:0 25px 50px rgba(0,0,0,.25);pointer-events:auto">
                <h3 style="font-size:1.125rem;font-weight:700;margin:0 0 .5rem">Abmelden?</h3>
                <p style="color:var(--text-sub);font-size:.875rem;margin:0 0 1.25rem">Bist du sicher, dass du dich abmelden möchtest?</p>
                <div style="display:flex;gap:.75rem">
                    <button @click="confirm=false" class="btn btn-o" style="flex:1">Abbrechen</button>
                    <form method="POST" action="{{ route('logout') }}" style="flex:1">
                        @csrf
                        <button type="submit" style="width:100%;padding:.625rem;border-radius:.5rem;background:var(--danger);color:#fff;font-weight:600;border:0;cursor:pointer;font-size:.9375rem;transition:opacity .15s">Abmelden</button>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
