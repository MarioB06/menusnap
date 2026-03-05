@extends('layouts.app')
@section('title', $restaurant->name)

@section('content')
<div class="cx" style="padding-top:1.5rem;padding-bottom:2rem" x-data="menuBrowser()" x-init="init()">

    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
        <a href="{{ route('browse') }}" style="padding:.5rem;color:var(--text-muted);border-radius:.5rem;transition:color .15s;display:flex" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">
            <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div style="display:flex;align-items:center;gap:.75rem;flex:1;min-width:0">
            @if($restaurant->logo_path)
                <img src="{{ Storage::disk('public')->url($restaurant->logo_path) }}" alt="{{ $restaurant->name }}" style="width:40px;height:40px;border-radius:.5rem;object-fit:cover;flex-shrink:0">
            @endif
            <div style="min-width:0">
                <h1 style="font-size:1.25rem;font-weight:700;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $restaurant->name }}</h1>
                @if($restaurant->address)
                    <p style="font-size:.875rem;color:var(--text-sub);margin:.125rem 0 0">{{ $restaurant->address }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="rel" style="margin-bottom:1rem">
        <svg style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:20px;height:20px;color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" x-model="searchQuery" placeholder="Gerichte suchen..." class="inp" style="padding-left:2.5rem;padding-right:2.5rem">
        <button x-show="searchQuery" @click="searchQuery=''" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;padding:0">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Category Chips --}}
    <div class="scx" style="margin-bottom:1rem" x-show="allCategories.length > 1">
        <template x-for="(cat, i) in allCategories" :key="cat.id">
            <button @click="selectedCategory = i" class="chip" :class="selectedCategory === i ? 'on' : ''" x-text="cat.name"></button>
        </template>
    </div>

    {{-- Dishes --}}
    <div class="g2">
        <template x-for="dish in filteredDishes" :key="dish.id">
            <button @click="openDish(dish)" class="dc">
                <div class="di">
                    <template x-if="dish.image_path">
                        <img :src="'/storage/' + dish.image_path" :alt="dish.name">
                    </template>
                    <template x-if="!dish.image_path">
                        <svg style="width:32px;height:32px;color:var(--text-muted);opacity:.3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </template>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem">
                        <p style="font-weight:500;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="dish.name"></p>
                        <span class="dp" x-text="fmt(dish.price)"></span>
                    </div>
                    <p style="font-size:.8125rem;color:var(--text-sub);margin:.25rem 0 0" class="lc2" x-text="dish.description || ''"></p>
                    <div style="display:flex;flex-wrap:wrap;gap:.25rem;margin-top:.5rem">
                        <template x-for="t in (dish.dietary_tags||[])" :key="t">
                            <span class="tag tag-g" x-text="t"></span>
                        </template>
                        <template x-for="a in (dish.allergens||[]).slice(0,2)" :key="a">
                            <span class="tag tag-a" x-text="a"></span>
                        </template>
                    </div>
                </div>
            </button>
        </template>
    </div>

    {{-- Empty --}}
    <div x-show="filteredDishes.length === 0" class="tc" style="padding:3rem 0">
        <svg style="width:48px;height:48px;color:var(--text-muted);margin:0 auto .75rem;display:block;opacity:.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <p style="color:var(--text-sub);margin:0">Keine Gerichte gefunden.</p>
    </div>

    {{-- Dish Modal --}}
    <div x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="ol" @keydown.escape.window="showModal=false" style="display:none" :style="showModal ? 'display:flex' : 'display:none'">
        <div class="ol-bg" @click="showModal=false"></div>
        <div class="ol-c" x-show="showModal" x-transition>
            <button @click="showModal=false" class="ol-x">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <template x-if="currentDish">
                <div>
                    <div class="ol-img">
                        <template x-if="currentDish.image_path"><img :src="'/storage/' + currentDish.image_path" :alt="currentDish.name"></template>
                        <template x-if="!currentDish.image_path"><svg style="width:64px;height:64px;color:var(--text-muted);opacity:.3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></template>
                    </div>
                    <div style="padding:1.5rem">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;margin-bottom:.75rem">
                            <h2 style="font-size:1.25rem;font-weight:700;margin:0" x-text="currentDish.name"></h2>
                            <span class="pb" x-text="fmt(currentDish.price)"></span>
                        </div>
                        <p style="color:var(--text-sub);margin:0 0 1rem;line-height:1.6" x-text="currentDish.description || 'Keine Beschreibung verfügbar.'"></p>
                        <template x-if="currentDish.dietary_tags && currentDish.dietary_tags.length > 0">
                            <div style="margin-bottom:.75rem">
                                <p style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin:0 0 .5rem">Ernährung</p>
                                <div style="display:flex;flex-wrap:wrap;gap:.375rem">
                                    <template x-for="t in currentDish.dietary_tags" :key="t"><span class="tag tag-g" style="font-size:.8125rem;padding:.25rem .625rem;border-radius:.5rem" x-text="t"></span></template>
                                </div>
                            </div>
                        </template>
                        <template x-if="currentDish.allergens && currentDish.allergens.length > 0">
                            <div>
                                <p style="font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin:0 0 .5rem">Allergene</p>
                                <div style="display:flex;flex-wrap:wrap;gap:.375rem">
                                    <template x-for="a in currentDish.allergens" :key="a"><span class="tag tag-a" style="font-size:.8125rem;padding:.25rem .625rem;border-radius:.5rem" x-text="a"></span></template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function menuBrowser() {
    return {
        searchQuery: '', selectedCategory: 0, allCategories: [], allDishes: {},
        showModal: false, currentDish: null,
        init() {
            const data = @json($restaurant->menus->flatMap(fn($m) => $m->categories)->values());
            this.allCategories = data;
            data.forEach(c => { this.allDishes[c.id] = c.dishes || []; });
            if (typeof Alpine !== 'undefined' && Alpine.store('recents')) {
                Alpine.store('recents').add({ id: {{ $restaurant->id }}, name: @json($restaurant->name), address: @json($restaurant->address) });
            }
        },
        get filteredDishes() {
            if (!this.allCategories.length) return [];
            let d = this.allDishes[this.allCategories[this.selectedCategory].id] || [];
            if (this.searchQuery.trim()) {
                const q = this.searchQuery.toLowerCase();
                d = d.filter(x => (x.name||'').toLowerCase().includes(q) || (x.description||'').toLowerCase().includes(q) || (x.dietary_tags||[]).some(t => t.toLowerCase().includes(q)) || (x.allergens||[]).some(a => a.toLowerCase().includes(q)));
            }
            return d;
        },
        openDish(d) { this.currentDish = d; this.showModal = true; },
        fmt(p) { return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(p); }
    };
}
</script>
@endsection
