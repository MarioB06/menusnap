@extends('layouts.app')
@section('title', 'QR-Tischkarte – ' . $menu->name)

@section('content')
<div class="cx" style="padding-top:1.5rem;padding-bottom:2rem" x-data="qrDesigner()">

    {{-- Header --}}
    <div style="margin-bottom:1.5rem">
        <a href="{{ route('web.restaurants.show', $restaurant) }}" style="font-size:.8125rem;color:var(--primary);display:inline-flex;align-items:center;gap:.25rem;margin-bottom:.75rem">
            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ $restaurant->name }}
        </a>
        <h1 style="font-size:1.25rem;font-weight:700;margin:0;color:var(--text)">QR-Tischkarte</h1>
        <p style="font-size:.875rem;color:var(--text-muted);margin:.25rem 0 0">Design-Vorlage für <b style="color:var(--text)">{{ $menu->name }}</b> wählen</p>
    </div>

    {{-- Main: Preview + Download (always visible, always works) --}}
    <div class="card" style="padding:1.5rem;margin-bottom:1.5rem">
        <div style="display:flex;flex-direction:column;align-items:center;gap:1rem">
            <div style="display:flex;align-items:center;gap:.5rem">
                <span style="font-size:.9375rem;font-weight:600;color:var(--text)" x-text="templateName"></span>
                <span x-show="canDownload" style="font-size:.625rem;font-weight:600;color:#15803d;background:#dcfce7;padding:.125rem .5rem;border-radius:9999px">Kostenlos</span>
                <span x-show="!canDownload" style="font-size:.625rem;font-weight:600;color:var(--primary);background:var(--primary-light);padding:.125rem .5rem;border-radius:9999px">Pro</span>
            </div>

            {{-- Preview --}}
            <div style="width:255px;height:360px;border:1px solid var(--border);border-radius:.75rem;overflow:hidden;background:#fff;box-shadow:0 4px 16px rgba(0,0,0,.1)">
                <iframe :src="previewUrl" style="width:100%;height:100%;border:0"></iframe>
            </div>

            {{-- Download Button --}}
            <form method="POST" action="{{ route('manage.menus.qr-designer.download', $menu) }}" style="width:100%;max-width:255px">
                @csrf
                <input type="hidden" name="template" :value="selected">
                <input type="hidden" name="custom_text" :value="customText">
                <input type="hidden" name="custom_color" :value="customColor">

                <template x-if="canDownload">
                    <button type="submit" class="btn btn-g" style="width:100%;padding:.75rem 1rem;font-size:.875rem">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Als PDF herunterladen
                    </button>
                </template>
                <template x-if="!canDownload">
                    <div style="padding:.75rem 1rem;background:var(--primary-light);border-radius:.75rem;text-align:center">
                        <p style="font-size:.8125rem;color:var(--primary);margin:0;font-weight:500">
                            Upgrade auf Pro für dieses Design
                        </p>
                    </div>
                </template>
            </form>
        </div>
    </div>

    {{-- Template Selection --}}
    <h2 style="font-size:.9375rem;font-weight:600;margin:0 0 .75rem;color:var(--text)">Vorlage wählen</h2>
    <div style="display:grid;grid-template-columns:repeat(2, 1fr);gap:.75rem" id="tmpl-grid">

        {{-- Minimal (Free) --}}
        <button x-on:click="selectTemplate('minimal')" :class="selected === 'minimal' ? 'qr-tmpl-active' : ''" class="qr-tmpl-btn">
            <div class="qr-tmpl-preview">
                <div style="text-align:center">
                    <div style="font-size:9px;font-weight:700;color:#111;margin-bottom:3px">{{ Str::limit($restaurant->name, 16) }}</div>
                    <div style="width:40px;height:40px;background:#d1d5db;margin:0 auto 3px"></div>
                    <div style="font-size:6px;color:#9ca3af">Speisekarte scannen</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;gap:.25rem">
                <span class="qr-tmpl-name">Minimal</span>
                <span style="font-size:.5625rem;font-weight:700;color:#15803d;background:#dcfce7;padding:1px 6px;border-radius:9999px">FREE</span>
            </div>
        </button>

        {{-- Classic (Pro) --}}
        <button x-on:click="selectTemplate('classic')" :class="selected === 'classic' ? 'qr-tmpl-active' : ''" class="qr-tmpl-btn">
            @if(!$isPro)<div class="qr-tmpl-lock"><svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>@endif
            <div class="qr-tmpl-preview" @if(!$isPro) style="filter:blur(2px)" @endif>
                <div style="text-align:center;border:1.5px solid #8b7355;padding:4px;background:#fdfbf7">
                    <div style="font-size:8px;font-weight:700;font-family:Georgia,serif;color:#2c1810;text-transform:uppercase">{{ Str::limit($restaurant->name, 12) }}</div>
                    <div style="font-size:6px;color:#c4a882;letter-spacing:2px">&#8226;&#8212;&#8226;</div>
                    <div style="width:36px;height:36px;background:#d1d5db;margin:2px auto;border:1px solid #d4c5a9"></div>
                </div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;gap:.25rem">
                <span class="qr-tmpl-name">Klassisch</span>
                @if(!$isPro)<span style="font-size:.5625rem;font-weight:700;color:var(--primary);background:var(--primary-light);padding:1px 6px;border-radius:9999px">PRO</span>@endif
            </div>
        </button>

        {{-- Modern (Pro) --}}
        <button x-on:click="selectTemplate('modern')" :class="selected === 'modern' ? 'qr-tmpl-active' : ''" class="qr-tmpl-btn">
            @if(!$isPro)<div class="qr-tmpl-lock"><svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>@endif
            <div class="qr-tmpl-preview" @if(!$isPro) style="filter:blur(2px)" @endif>
                <div style="text-align:center;overflow:hidden;border-radius:4px;width:70px">
                    <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:3px;color:#fff;font-size:7px;font-weight:700">{{ Str::limit($restaurant->name, 12) }}</div>
                    <div style="padding:3px;background:#fff">
                        <div style="width:36px;height:36px;background:#d1d5db;margin:0 auto 2px;border-radius:2px"></div>
                        <div style="font-size:6px;color:#4f46e5;font-weight:600">Scannen</div>
                    </div>
                </div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;gap:.25rem">
                <span class="qr-tmpl-name">Modern</span>
                @if(!$isPro)<span style="font-size:.5625rem;font-weight:700;color:var(--primary);background:var(--primary-light);padding:1px 6px;border-radius:9999px">PRO</span>@endif
            </div>
        </button>

        {{-- Custom (Pro) --}}
        <button x-on:click="selectTemplate('custom')" :class="selected === 'custom' ? 'qr-tmpl-active' : ''" class="qr-tmpl-btn">
            @if(!$isPro)<div class="qr-tmpl-lock"><svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>@endif
            <div class="qr-tmpl-preview" @if(!$isPro) style="filter:blur(2px)" @endif>
                <div style="text-align:center;width:70px">
                    <div style="height:3px;background:#4f46e5;border-radius:2px 2px 0 0"></div>
                    <div style="padding:4px">
                        <div style="font-size:7px;font-weight:700;color:#4f46e5">{{ Str::limit($restaurant->name, 12) }}</div>
                        <div style="width:36px;height:36px;background:#d1d5db;margin:2px auto;border:1.5px solid #4f46e5;border-radius:2px"></div>
                    </div>
                    <div style="height:3px;background:#4f46e5;border-radius:0 0 2px 2px"></div>
                </div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;gap:.25rem">
                <span class="qr-tmpl-name">Eigenes</span>
                @if(!$isPro)<span style="font-size:.5625rem;font-weight:700;color:var(--primary);background:var(--primary-light);padding:1px 6px;border-radius:9999px">PRO</span>@endif
            </div>
        </button>
    </div>

    {{-- Custom Options (only for Pro + custom template) --}}
    @if($isPro)
    <div x-show="selected === 'custom'" x-transition style="margin-top:1rem">
        <div class="card" style="padding:1rem">
            <h3 style="font-size:.875rem;font-weight:600;margin:0 0 .75rem;color:var(--text)">Anpassen</h3>
            <div style="margin-bottom:.75rem">
                <label class="lbl">Eigener Text</label>
                <input x-model.debounce.500ms="customText" class="inp" placeholder="z.B. Willkommen!" style="font-size:.875rem">
            </div>
            <div>
                <label class="lbl">Akzentfarbe</label>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <input type="color" x-model="customColor" style="width:2.5rem;height:2.5rem;border:1px solid var(--border);border-radius:.375rem;cursor:pointer;padding:2px;background:var(--bg-card)">
                    <span style="font-size:.8125rem;color:var(--text-muted);font-family:monospace" x-text="customColor"></span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    @media(min-width:640px) {
        #tmpl-grid { grid-template-columns: repeat(4, 1fr) !important; }
    }
    .qr-tmpl-btn {
        background: var(--bg-card);
        border: 2px solid var(--border);
        border-radius: .75rem;
        padding: .625rem;
        cursor: pointer;
        text-align: center;
        transition: all .15s;
        position: relative;
        overflow: hidden;
    }
    .qr-tmpl-btn:hover {
        border-color: color-mix(in srgb, var(--primary) 50%, transparent);
    }
    .qr-tmpl-active {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, .2);
    }
    .qr-tmpl-preview {
        height: 5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: .5rem;
    }
    .qr-tmpl-name {
        font-size: .75rem;
        font-weight: 600;
        color: var(--text);
    }
    .qr-tmpl-lock {
        position: absolute;
        inset: 0;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        pointer-events: none;
    }
</style>

<script>
function qrDesigner() {
    const isPro = @json($isPro);
    const menuId = {{ $menu->id }};
    const basePreviewUrl = '/menus/' + menuId + '/qr-designer/preview';
    const freeTemplates = @json(collect($templates)->filter(fn($t) => $t['free'])->keys()->values());
    const templateNames = @json(collect($templates)->mapWithKeys(fn($t, $k) => [$k => $t['name']]));

    return {
        selected: 'minimal',
        customText: '',
        customColor: '#4f46e5',
        isPro,

        get templateName() {
            return templateNames[this.selected] || '';
        },

        get canDownload() {
            if (this.isPro) return true;
            return freeTemplates.includes(this.selected);
        },

        get previewUrl() {
            let url = basePreviewUrl + '?template=' + encodeURIComponent(this.selected);
            if (this.selected === 'custom') {
                if (this.customText) url += '&custom_text=' + encodeURIComponent(this.customText);
                if (this.customColor) url += '&custom_color=' + encodeURIComponent(this.customColor);
            }
            return url;
        },

        selectTemplate(id) {
            this.selected = id;
        }
    };
}
</script>
@endsection
