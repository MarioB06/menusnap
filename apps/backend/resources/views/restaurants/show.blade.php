@extends('layouts.app')
@section('title', $restaurant->name)

@section('content')
<div class="cx" style="padding-top:1.5rem;padding-bottom:2rem" x-data="manager()">

    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem">
        <div style="flex:1;min-width:0">
            <h1 style="font-size:1.25rem;font-weight:700;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $restaurant->name }}</h1>
            <p style="font-size:.8125rem;color:var(--text-muted);margin:.125rem 0 0">{{ $restaurant->address ?? 'Keine Adresse' }}</p>
        </div>
        <a href="{{ route('web.restaurants.edit', $restaurant) }}" class="btn btn-o" style="width:auto;padding:.5rem 1rem;font-size:.8125rem">Bearbeiten</a>
    </div>

    {{-- Tabs --}}
    <div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:1.5rem">
        <button @click="tab='menus'" :style="tab==='menus' ? 'border-bottom:2px solid var(--primary);color:var(--primary);margin-bottom:-2px' : 'color:var(--text-muted)'"
                style="padding:.75rem 1.25rem;font-size:.875rem;font-weight:600;background:0;border:0;cursor:pointer;transition:color .15s">
            Menüs
        </button>
        <button @click="tab='tables'" :style="tab==='tables' ? 'border-bottom:2px solid var(--primary);color:var(--primary);margin-bottom:-2px' : 'color:var(--text-muted)'"
                style="padding:.75rem 1.25rem;font-size:.875rem;font-weight:600;background:0;border:0;cursor:pointer;transition:color .15s">
            Tische
        </button>
    </div>

    {{-- ═══ MENUS TAB ═══ --}}
    <div x-show="tab==='menus'">

        {{-- Add Menu --}}
        <div style="margin-bottom:1.5rem" x-data="{adding:false,name:''}">
            <button x-show="!adding" @click="adding=true" class="btn btn-o" style="width:auto;padding:.5rem 1rem;font-size:.8125rem">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Menü hinzufügen
            </button>
            <div x-show="adding" x-transition style="display:flex;gap:.5rem">
                <input x-model="name" @keydown.enter="if(name.trim()){addMenu(name);name='';adding=false}" @keydown.escape="adding=false" x-ref="menuInput" class="inp" placeholder="Menü-Name" style="flex:1" x-init="$watch('adding',v=>{if(v)$nextTick(()=>$refs.menuInput.focus())})">
                <button @click="if(name.trim()){addMenu(name);name='';adding=false}" class="btn btn-p" style="width:auto;padding:.5rem 1rem">Erstellen</button>
                <button @click="adding=false;name=''" class="btn btn-o" style="width:auto;padding:.5rem .75rem">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Menu List --}}
        <template x-if="menus.length === 0">
            <div class="card tc" style="padding:2rem">
                <p style="color:var(--text-muted);margin:0">Noch keine Menüs erstellt.</p>
            </div>
        </template>

        <div style="display:flex;flex-direction:column;gap:1rem">
            <template x-for="menu in menus" :key="menu.id">
                <div class="card" style="overflow:hidden">
                    {{-- Menu Header --}}
                    <div style="padding:1rem 1.25rem;display:flex;align-items:center;gap:.75rem;cursor:pointer;border-bottom:1px solid var(--border)" @click="toggleMenu(menu.id)">
                        <svg width="16" height="16" :style="'width:16px;height:16px;color:var(--text-muted);transition:transform .15s;flex-shrink:0' + (openMenus.includes(menu.id) ? ';transform:rotate(90deg)' : '')" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <div style="flex:1;min-width:0">
                            <span style="font-weight:600" x-text="menu.name"></span>
                            <span style="font-size:.75rem;color:var(--text-muted);margin-left:.5rem" x-text="'(' + (menu.categories||[]).reduce((a,c)=>a+(c.dishes||[]).length,0) + ' Gerichte)'"></span>
                        </div>
                        <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0" :style="'background:' + (menu.is_active!==false ? '#22c55e' : '#ef4444')"></span>
                        <a @click.stop :href="'/menus/' + menu.id + '/qr-designer'" style="padding:.25rem;color:var(--primary);display:flex;transition:opacity .15s" title="QR-Tischkarte gestalten" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        </a>
                        <a @click.stop :href="'/menus/' + menu.id + '/qr'" style="padding:.25rem;color:var(--primary);display:flex;transition:opacity .15s" title="QR-Code herunterladen" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                        <button @click.stop="deleteMenu(menu)" style="padding:.25rem;color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;transition:color .15s" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>

                    {{-- Menu Content (Categories + Dishes) --}}
                    <div x-show="openMenus.includes(menu.id)" x-transition style="padding:1rem 1.25rem">

                        {{-- Categories --}}
                        <template x-for="cat in (menu.categories||[])" :key="cat.id">
                            <div style="margin-bottom:1.25rem">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                                    <h3 style="font-size:.9375rem;font-weight:600;margin:0" x-text="cat.name"></h3>
                                    <div style="display:flex;gap:.25rem">
                                        <button @click="promptDish(cat)" style="padding:.25rem;color:var(--primary);background:0;border:0;cursor:pointer;display:flex" title="Gericht hinzufügen">
                                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                        <button @click="deleteCategory(cat, menu)" style="padding:.25rem;color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;transition:color .15s" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'" title="Kategorie löschen">
                                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Dishes in category --}}
                                <template x-for="dish in (cat.dishes||[])" :key="dish.id">
                                    <div style="display:flex;align-items:center;gap:.75rem;padding:.5rem .75rem;border-radius:.5rem;margin-bottom:.25rem;background:color-mix(in srgb,var(--text) 3%,transparent)">
                                        <div style="flex:1;min-width:0">
                                            <span style="font-size:.875rem;font-weight:500" x-text="dish.name"></span>
                                            <span style="font-size:.8125rem;color:var(--text-muted);margin-left:.5rem" x-text="dish.description ? '— ' + dish.description.substring(0,40) + (dish.description.length > 40 ? '...' : '') : ''"></span>
                                        </div>
                                        <span style="font-size:.8125rem;font-weight:600;color:var(--primary);white-space:nowrap" x-text="fmt(dish.price)"></span>
                                        <button @click="editDish(dish, cat)" style="padding:.25rem;color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;transition:color .15s" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'" title="Bearbeiten">
                                            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button @click="deleteDish(dish, cat)" style="padding:.25rem;color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;transition:color .15s" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'" title="Löschen">
                                            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <template x-if="(cat.dishes||[]).length === 0">
                                    <p style="font-size:.8125rem;color:var(--text-muted);margin:0;padding:.25rem .75rem">Keine Gerichte in dieser Kategorie.</p>
                                </template>
                            </div>
                        </template>

                        {{-- Add Category --}}
                        <div x-data="{adding:false,name:''}" style="margin-top:.75rem">
                            <button x-show="!adding" @click="adding=true" style="font-size:.8125rem;color:var(--primary);background:0;border:0;cursor:pointer;display:flex;align-items:center;gap:.25rem;padding:.25rem 0">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Kategorie hinzufügen
                            </button>
                            <div x-show="adding" x-transition style="display:flex;gap:.5rem">
                                <input x-model="name" @keydown.enter="if(name.trim()){addCategory(menu,name);name='';adding=false}" @keydown.escape="adding=false" class="inp" placeholder="Kategorie-Name" style="flex:1;font-size:.875rem;padding:.375rem .75rem">
                                <button @click="if(name.trim()){addCategory(menu,name);name='';adding=false}" class="btn btn-p" style="width:auto;padding:.375rem .75rem;font-size:.8125rem">OK</button>
                                <button @click="adding=false;name=''" style="padding:.375rem;color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex">
                                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ═══ TABLES TAB ═══ --}}
    <div x-show="tab==='tables'">
        <div style="margin-bottom:1.5rem" x-data="{adding:false,label:''}">
            <button x-show="!adding" @click="adding=true" class="btn btn-o" style="width:auto;padding:.5rem 1rem;font-size:.8125rem">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tisch hinzufügen
            </button>
            <div x-show="adding" x-transition style="display:flex;gap:.5rem">
                <input x-model="label" @keydown.enter="if(label.trim()){addTable(label);label='';adding=false}" @keydown.escape="adding=false" x-ref="tableInput" class="inp" placeholder="z.B. Tisch 1" style="flex:1" x-init="$watch('adding',v=>{if(v)$nextTick(()=>$refs.tableInput.focus())})">
                <button @click="if(label.trim()){addTable(label);label='';adding=false}" class="btn btn-p" style="width:auto;padding:.5rem 1rem">Erstellen</button>
                <button @click="adding=false;label=''" class="btn btn-o" style="width:auto;padding:.5rem .75rem">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <template x-if="tables.length === 0">
            <div class="card tc" style="padding:2rem">
                <p style="color:var(--text-muted);margin:0">Noch keine Tische erstellt.</p>
            </div>
        </template>

        <div class="g2">
            <template x-for="table in tables" :key="table.id">
                <div class="card" style="padding:1rem;display:flex;align-items:center;gap:1rem">
                    <div style="width:3rem;height:3rem;border-radius:.5rem;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg style="width:20px;height:20px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-weight:500;margin:0" x-text="table.label"></p>
                        <p style="font-size:.75rem;color:var(--text-muted);margin:.125rem 0 0;font-family:monospace" x-text="table.uuid ? table.uuid.substring(0,8) + '...' : ''"></p>
                    </div>
                    <a :href="'/tables/' + table.id + '/qr'" style="padding:.375rem;color:var(--primary);display:flex" title="QR-Code herunterladen">
                        <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                    <button @click="deleteTable(table)" style="padding:.375rem;color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;transition:color .15s" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'" title="Löschen">
                        <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- ═══ DISH EDIT MODAL ═══ --}}
    <div x-show="dishModal" x-transition style="position:fixed;inset:0;z-index:50">
        <div style="position:absolute;inset:0;background:rgba(0,0,0,.5)" @click="dishModal=false"></div>
        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none">
        <div style="position:relative;background:var(--bg-card);border-radius:1rem;padding:1.5rem;width:100%;max-width:28rem;margin:0 1rem;box-shadow:0 25px 50px rgba(0,0,0,.25);max-height:85vh;overflow-y:auto;pointer-events:auto">
            <h3 style="font-size:1.125rem;font-weight:700;margin:0 0 1rem" x-text="dishForm.id ? 'Gericht bearbeiten' : 'Neues Gericht'"></h3>

            <div style="margin-bottom:1rem">
                <label class="lbl">Name *</label>
                <input x-model="dishForm.name" class="inp" placeholder="z.B. Margherita">
            </div>
            <div style="margin-bottom:1rem">
                <label class="lbl">Beschreibung</label>
                <textarea x-model="dishForm.description" class="inp" rows="2" placeholder="Kurze Beschreibung" style="resize:vertical"></textarea>
            </div>
            <div style="margin-bottom:1rem">
                <label class="lbl">Preis (EUR) *</label>
                <input x-model="dishForm.price" type="number" step="0.01" min="0" class="inp" placeholder="12.50">
            </div>
            <div style="margin-bottom:1rem">
                <label class="lbl">Allergene (kommagetrennt)</label>
                <input x-model="dishForm.allergensStr" class="inp" placeholder="Gluten, Laktose, Nüsse">
            </div>
            <div style="margin-bottom:1rem">
                <label class="lbl">Ernährung (kommagetrennt)</label>
                <input x-model="dishForm.tagsStr" class="inp" placeholder="Vegan, Glutenfrei">
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem">
                <input type="checkbox" x-model="dishForm.is_available" style="accent-color:var(--primary)">
                <label style="font-size:.875rem;color:var(--text-sub);margin:0">Verfügbar</label>
            </div>

            <p x-show="dishError" x-text="dishError" style="color:var(--danger);font-size:.8125rem;margin:0 0 .75rem"></p>

            <div style="display:flex;gap:.75rem">
                <button @click="dishModal=false" class="btn btn-o" style="flex:1">Abbrechen</button>
                <button @click="saveDish()" class="btn btn-p" style="flex:1">Speichern</button>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
function manager() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const rId = {{ $restaurant->id }};

    return {
        tab: 'menus',
        menus: @json($restaurant->menus),
        tables: @json($restaurant->tables),
        openMenus: [],
        dishModal: false,
        dishForm: { id: null, name: '', description: '', price: '', allergensStr: '', tagsStr: '', is_available: true, _catId: null },
        dishError: '',

        toggleMenu(id) {
            if (this.openMenus.includes(id)) this.openMenus = this.openMenus.filter(x => x !== id);
            else this.openMenus.push(id);
        },

        async req(url, method, body) {
            const opts = { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } };
            if (body) opts.body = JSON.stringify(body);
            const r = await fetch(url, opts);
            if (!r.ok) { const e = await r.json().catch(() => ({})); throw new Error(e.error || e.message || 'Fehler'); }
            if (r.status === 204) return null;
            return r.json();
        },

        fmt(p) { return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(p); },

        // ── Menus ──
        async addMenu(name) {
            try {
                const m = await this.req('/restaurants/' + rId + '/menus', 'POST', { name });
                if (!m.categories) m.categories = [];
                this.menus.push(m);
                this.openMenus.push(m.id);
            } catch (e) { alert(e.message); }
        },
        async deleteMenu(menu) {
            if (!confirm('Menü "' + menu.name + '" mit allen Kategorien und Gerichten löschen?')) return;
            try { await this.req('/menus/' + menu.id, 'DELETE'); this.menus = this.menus.filter(m => m.id !== menu.id); } catch (e) { alert(e.message); }
        },

        // ── Categories ──
        async addCategory(menu, name) {
            try {
                const c = await this.req('/menus/' + menu.id + '/categories', 'POST', { name });
                if (!c.dishes) c.dishes = [];
                const m = this.menus.find(x => x.id === menu.id);
                if (m) { if (!m.categories) m.categories = []; m.categories.push(c); }
            } catch (e) { alert(e.message); }
        },
        async deleteCategory(cat, menu) {
            if (!confirm('Kategorie "' + cat.name + '" mit allen Gerichten löschen?')) return;
            try {
                await this.req('/categories/' + cat.id, 'DELETE');
                const m = this.menus.find(x => x.id === menu.id);
                if (m) m.categories = (m.categories || []).filter(c => c.id !== cat.id);
            } catch (e) { alert(e.message); }
        },

        // ── Dishes ──
        promptDish(cat) {
            this.dishForm = { id: null, name: '', description: '', price: '', allergensStr: '', tagsStr: '', is_available: true, _catId: cat.id };
            this.dishError = '';
            this.dishModal = true;
        },
        editDish(dish, cat) {
            this.dishForm = {
                id: dish.id,
                name: dish.name,
                description: dish.description || '',
                price: dish.price,
                allergensStr: (dish.allergens || []).join(', '),
                tagsStr: (dish.dietary_tags || []).join(', '),
                is_available: dish.is_available !== false,
                _catId: cat.id
            };
            this.dishError = '';
            this.dishModal = true;
        },
        async saveDish() {
            const f = this.dishForm;
            if (!f.name.trim() || !f.price) { this.dishError = 'Name und Preis sind Pflichtfelder.'; return; }
            const data = {
                name: f.name.trim(),
                description: f.description.trim() || null,
                price: parseFloat(f.price),
                allergens: f.allergensStr ? f.allergensStr.split(',').map(s => s.trim()).filter(Boolean) : [],
                dietary_tags: f.tagsStr ? f.tagsStr.split(',').map(s => s.trim()).filter(Boolean) : [],
                is_available: f.is_available
            };
            try {
                if (f.id) {
                    const d = await this.req('/dishes/' + f.id, 'PUT', data);
                    this.updateDishInMenus(f._catId, f.id, d);
                } else {
                    const d = await this.req('/categories/' + f._catId + '/dishes', 'POST', data);
                    this.addDishToCategory(f._catId, d);
                }
                this.dishModal = false;
            } catch (e) { this.dishError = e.message; }
        },
        async deleteDish(dish, cat) {
            if (!confirm('Gericht "' + dish.name + '" löschen?')) return;
            try {
                await this.req('/dishes/' + dish.id, 'DELETE');
                for (const m of this.menus) for (const c of (m.categories || [])) {
                    if (c.id === cat.id) c.dishes = (c.dishes || []).filter(d => d.id !== dish.id);
                }
            } catch (e) { alert(e.message); }
        },
        addDishToCategory(catId, dish) {
            for (const m of this.menus) for (const c of (m.categories || [])) {
                if (c.id === catId) { if (!c.dishes) c.dishes = []; c.dishes.push(dish); }
            }
        },
        updateDishInMenus(catId, dishId, data) {
            for (const m of this.menus) for (const c of (m.categories || [])) {
                if (c.id === catId) c.dishes = (c.dishes || []).map(d => d.id === dishId ? { ...d, ...data } : d);
            }
        },

        // ── Tables ──
        async addTable(label) {
            try { const t = await this.req('/restaurants/' + rId + '/tables', 'POST', { label }); this.tables.push(t); } catch (e) { alert(e.message); }
        },
        async deleteTable(table) {
            if (!confirm('Tisch "' + table.label + '" löschen?')) return;
            try { await this.req('/tables/' + table.id, 'DELETE'); this.tables = this.tables.filter(t => t.id !== table.id); } catch (e) { alert(e.message); }
        }
    };
}
</script>
@endsection
