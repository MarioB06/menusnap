@extends('layouts.app')
@section('title', 'Restaurant suchen')

@section('content')
<div style="min-height:calc(100vh - 10rem);display:flex;align-items:center;justify-content:center;padding:3rem 1rem">
    <div class="cx-md">

        {{-- Code Input --}}
        <div class="tc" style="margin-bottom:2rem">
            <div class="ib ib-l">
                <svg style="width:32px;height:32px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <h1 style="font-size:1.5rem;font-weight:700;margin:0 0 .375rem">Restaurant-Code eingeben</h1>
            <p class="ts" style="margin:0;font-size:.875rem">Gib den Code ein, der auf dem Tisch oder der Karte steht.</p>
        </div>

        <form method="POST" action="{{ route('browse.lookup') }}" style="margin-bottom:0" x-data="{code:'{{ old('code','') }}'}">
            @csrf
            <div style="display:flex;gap:.75rem">
                <input type="text" name="code" x-model="code" required class="inp" placeholder="Code oder Slug" style="text-align:center;font-size:1.125rem;letter-spacing:.05em;flex:1">
                <button type="submit" :disabled="code.length < 1" class="btn btn-p" style="width:auto;padding:.625rem 1.5rem" :style="code.length < 1 ? 'opacity:.5;cursor:not-allowed' : ''">
                    Öffnen
                </button>
            </div>
            @error('code')<p class="err tc" style="margin-top:.5rem">{{ $message }}</p>@enderror
        </form>

        {{-- Divider --}}
        <div class="dv">
            <span>Oder QR-Code scannen</span>
        </div>

        {{-- QR Scanner --}}
        <div id="scanner" x-data="qrScanner()" class="tc">
            <div x-show="!scanning">
                <button @click="startScanner()" class="btn btn-o" style="width:auto;margin:0 auto">
                    <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9V5a2 2 0 012-2h4M3 15v4a2 2 0 002 2h4m8-18h4a2 2 0 012 2v4m0 6v4a2 2 0 01-2 2h-4"/></svg>
                    Kamera öffnen
                </button>
            </div>

            <div x-show="scanning" x-transition>
                <div id="qr-reader" style="border-radius:.75rem;overflow:hidden"></div>
                <button @click="stopScanner()" style="margin-top:.75rem;font-size:.875rem;color:var(--text-muted);background:0;border:0;cursor:pointer">
                    Kamera schließen
                </button>
            </div>

            <p x-show="error" x-text="error" class="err" style="margin-top:.75rem"></p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function qrScanner() {
    return {
        scanning: false, scanner: null, error: '',
        startScanner() {
            this.error = '';
            this.scanning = true;
            this.$nextTick(() => {
                this.scanner = new Html5Qrcode('qr-reader');
                this.scanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (text) => this.handleScan(text),
                    () => {}
                ).catch(() => {
                    this.error = 'Kamera konnte nicht geöffnet werden. Bitte erlaube den Kamerazugriff.';
                    this.scanning = false;
                });
            });
        },
        stopScanner() {
            if (this.scanner) this.scanner.stop().then(() => { this.scanner.clear(); this.scanning = false; }).catch(() => { this.scanning = false; });
        },
        handleScan(text) {
            this.stopScanner();
            const m = text.match(/\/menu\/([^\/]+)\/([^\/\s?#]+)/);
            if (m) { window.location.href = '/menu/' + m[1] + '/' + m[2]; return; }
            const b = text.match(/\/browse\/(\d+)/);
            if (b) { window.location.href = '/browse/' + b[1]; return; }
            if (/^\d+$/.test(text.trim())) { window.location.href = '/browse/' + text.trim(); return; }
            if (/^[a-z0-9-]+$/i.test(text.trim())) {
                const f = document.createElement('form'); f.method = 'POST'; f.action = '{{ route("browse.lookup") }}';
                const t = document.createElement('input'); t.type = 'hidden'; t.name = '_token'; t.value = document.querySelector('meta[name="csrf-token"]').content;
                const c = document.createElement('input'); c.type = 'hidden'; c.name = 'code'; c.value = text.trim();
                f.appendChild(t); f.appendChild(c); document.body.appendChild(f); f.submit(); return;
            }
            this.error = 'QR-Code konnte nicht erkannt werden.';
        }
    };
}
</script>
@endsection
