@extends('layouts.app')
@section('title', 'Anmelden')

@section('content')
<div style="min-height:calc(100vh - 10rem);display:flex;align-items:center;justify-content:center;padding:3rem 1rem">
    <div class="cx-sm">

        <div class="tc" style="margin-bottom:2rem">
            <div class="ib ib-l">
                <img src="/images/logoNoName.png" alt="MenuSnap" style="height:32px;width:auto">
            </div>
            <h1 style="font-size:1.5rem;font-weight:700;margin:0 0 .25rem">Willkommen zurück</h1>
            <p class="ts" style="margin:0;font-size:.9375rem">Melde dich an, um fortzufahren</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="card" style="padding:1.5rem">
            @csrf

            <div style="margin-bottom:1.25rem">
                <label for="email" class="lbl">E-Mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="inp" placeholder="deine@email.de">
                @error('email')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:1.25rem" x-data="{show:false}">
                <label for="password" class="lbl">Passwort</label>
                <div class="rel">
                    <input :type="show?'text':'password'" id="password" name="password" required class="inp" placeholder="Passwort" style="padding-right:2.5rem">
                    <button type="button" @click="show=!show" class="pwd-toggle">
                        <svg x-show="!show" style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem">
                <input type="checkbox" id="remember" name="remember" style="accent-color:var(--primary)">
                <label for="remember" class="fs ts" style="margin:0">Angemeldet bleiben</label>
            </div>

            <button type="submit" class="btn btn-p">Anmelden</button>
        </form>

        <div class="tc" style="margin-top:1.5rem">
            <p class="fs ts" style="margin:0 0 .75rem">
                Noch kein Konto?
                <a href="{{ route('register') }}" class="tp fs6" style="text-decoration:none">Registrieren</a>
            </p>
            <a href="{{ route('browse') }}" class="tm fs" style="transition:color .15s">Ohne Anmeldung fortfahren</a>
        </div>
    </div>
</div>
@endsection
