<!DOCTYPE html>
<html lang="de" id="app-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – MenuSnap</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        :root {
            --bg: #f9fafb; --bg-card: #fff; --bg-input: #fff;
            --border: #e5e7eb; --border-input: #d1d5db;
            --text: #111827; --text-sub: #6b7280; --text-muted: #9ca3af;
            --primary: #4f46e5; --primary-h: #4338ca; --primary-light: #eef2ff;
            --danger: #dc2626; --danger-light: #fef2f2; --danger-border: #fecaca;
        }
        .dark {
            --bg: #0f172a; --bg-card: #1e293b; --bg-input: #334155;
            --border: #334155; --border-input: #475569;
            --text: #f1f5f9; --text-sub: #94a3b8; --text-muted: #64748b;
            --primary: #818cf8; --primary-h: #6366f1; --primary-light: rgba(99,102,241,.15);
            --danger: #f87171; --danger-light: rgba(239,68,68,.1); --danger-border: rgba(239,68,68,.3);
        }
        *,*::before,*::after{box-sizing:border-box}
        body{margin:0;background:var(--bg);color:var(--text);font-family:'Instrument Sans',system-ui,-apple-system,sans-serif;min-height:100vh;display:flex;flex-direction:column;-webkit-font-smoothing:antialiased}
        a{color:inherit;text-decoration:none}

        /* Nav */
        .nav{background:var(--bg-card);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:40}
        .nav-in{max-width:64rem;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:4rem}
        .nav-logo{display:flex;align-items:center;gap:.5rem}
        .nav-logo img{height:26px;width:auto}
        .nav-logo b{font-size:1.125rem;color:var(--primary)}
        .nav-r{display:flex;align-items:center;gap:1rem}
        .nav-link{font-size:.875rem;color:var(--text-sub);transition:color .15s}
        .nav-link:hover{color:var(--primary)}
        .nav-link-p{color:var(--primary);font-weight:600}
        .nav-btn{background:var(--primary);color:#fff;font-size:.875rem;font-weight:600;padding:.5rem 1rem;border-radius:.5rem;transition:background .15s;border:none;cursor:pointer}
        .nav-btn:hover{background:var(--primary-h)}
        .nav-icon{padding:.375rem;color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;align-items:center;transition:color .15s}
        .nav-icon:hover{color:var(--primary)}
        .nav-out{font-size:.875rem;color:var(--text-muted);background:0;border:0;cursor:pointer;transition:color .15s}
        .nav-out:hover{color:var(--danger)}

        /* Layout */
        main{flex:1}
        footer.f{padding:1.5rem;text-align:center;border-top:1px solid var(--border)}
        footer.f a{display:inline-flex;align-items:center;gap:.5rem;font-size:.75rem;color:var(--text-muted)}
        footer.f img{height:14px;width:auto;opacity:.4}

        /* Flash */
        .flash{max-width:64rem;margin:1rem auto 0;padding:0 1.5rem}
        .flash-s{padding:.75rem 1rem;border-radius:.5rem;font-size:.875rem;background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d}
        .flash-e{padding:.75rem 1rem;border-radius:.5rem;font-size:.875rem;background:#fef2f2;border:1px solid #fecaca;color:#b91c1c}
        .dark .flash-s{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.3);color:#4ade80}
        .dark .flash-e{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#f87171}

        /* Containers */
        .cx{max-width:64rem;margin:0 auto;padding:2rem 1.5rem}
        .cx-sm{max-width:28rem;margin:0 auto;padding:2rem 1rem}
        .cx-md{max-width:32rem;margin:0 auto;padding:2rem 1rem}

        /* Card */
        .card{background:var(--bg-card);border:1px solid var(--border);border-radius:1rem}
        .card:hover.card-h{border-color:color-mix(in srgb,var(--primary) 40%,transparent);box-shadow:0 1px 3px rgba(0,0,0,.06)}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;font-weight:600;border:none;cursor:pointer;transition:all .15s;font-size:.9375rem;border-radius:.5rem}
        .btn-p{background:var(--primary);color:#fff;padding:.625rem 1.5rem;width:100%}
        .btn-p:hover{background:var(--primary-h)}
        .btn-g{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;padding:.75rem 1.5rem;border-radius:.75rem;box-shadow:0 4px 14px rgba(79,70,229,.3)}
        .btn-g:hover{opacity:.9}
        .btn-o{background:var(--bg-card);color:var(--text);padding:.75rem 1.5rem;border-radius:.75rem;border:1px solid var(--border)}
        .btn-o:hover{border-color:color-mix(in srgb,var(--primary) 40%,transparent)}
        .btn-d{background:var(--danger-light);color:var(--danger);padding:.75rem;border-radius:.75rem;border:1px solid var(--danger-border);width:100%}
        .btn-d:hover{opacity:.85}

        /* Inputs */
        .inp{width:100%;padding:.625rem 1rem;border-radius:.5rem;border:1px solid var(--border-input);background:var(--bg-input);color:var(--text);font-size:.9375rem;outline:0;transition:border .15s,box-shadow .15s;box-sizing:border-box}
        .inp:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,70,229,.15)}
        .inp::placeholder{color:var(--text-muted)}
        .lbl{display:block;font-size:.875rem;font-weight:500;color:var(--text-sub);margin-bottom:.375rem}
        .err{color:#ef4444;font-size:.8125rem;margin-top:.25rem}

        /* Icon box */
        .ib{width:4rem;height:4rem;border-radius:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem}
        .ib-l{background:var(--primary-light)}
        .ib-g{background:linear-gradient(135deg,#4f46e5,#7c3aed);box-shadow:0 4px 14px rgba(79,70,229,.3)}
        .ib-s{width:2.5rem;height:2.5rem;border-radius:.5rem;margin:0}

        /* Avatar */
        .av{width:3.5rem;height:3.5rem;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.25rem;font-weight:700;flex-shrink:0}

        /* Theme toggle */
        .tt{display:flex;background:color-mix(in srgb,var(--text) 8%,transparent);border-radius:.5rem;padding:2px}
        .tt button{padding:.375rem .75rem;border-radius:.375rem;font-size:.8125rem;font-weight:500;color:var(--text-sub);background:0;border:0;cursor:pointer;transition:all .15s}
        .tt button.on{background:var(--bg-card);box-shadow:0 1px 2px rgba(0,0,0,.08);color:var(--text)}

        /* Tags */
        .tag{display:inline-block;font-size:.75rem;padding:.125rem .5rem;border-radius:.25rem}
        .tag-g{background:#dcfce7;color:#15803d}
        .tag-a{background:#fef3c7;color:#92400e}
        .dark .tag-g{background:rgba(34,197,94,.15);color:#4ade80}
        .dark .tag-a{background:rgba(245,158,11,.15);color:#fbbf24}

        /* Chips */
        .chip{display:inline-block;padding:.375rem 1rem;border-radius:9999px;font-size:.875rem;font-weight:500;border:1px solid var(--border);background:var(--bg-card);color:var(--text-sub);cursor:pointer;transition:all .15s;white-space:nowrap}
        .chip.on{background:var(--primary);color:#fff;border-color:var(--primary)}

        /* Scroll */
        .scx{overflow-x:auto;-ms-overflow-style:none;scrollbar-width:none;display:flex;gap:.5rem;padding-bottom:.5rem}
        .scx::-webkit-scrollbar{display:none}

        /* Dish */
        .dc{display:flex;gap:.75rem;padding:.75rem;background:var(--bg-card);border:1px solid var(--border);border-radius:.75rem;cursor:pointer;transition:all .15s;text-align:left;width:100%}
        .dc:hover{border-color:color-mix(in srgb,var(--primary) 40%,transparent);box-shadow:0 1px 3px rgba(0,0,0,.06)}
        .di{width:5rem;height:5rem;border-radius:.5rem;background:color-mix(in srgb,var(--text) 5%,transparent);flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center}
        .di img{width:100%;height:100%;object-fit:cover}
        .dp{color:var(--primary);font-weight:600;font-size:.875rem;white-space:nowrap}
        .lc2{overflow:hidden;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2}

        /* Modal */
        .ol{position:fixed;inset:0;z-index:50;display:flex;align-items:flex-end;justify-content:center}
        @media(min-width:640px){.ol{align-items:center}}
        .ol-bg{position:absolute;inset:0;background:rgba(0,0,0,.5)}
        .ol-c{position:relative;width:100%;max-width:32rem;background:var(--bg-card);border-radius:1.5rem 1.5rem 0 0;max-height:85vh;overflow-y:auto}
        @media(min-width:640px){.ol-c{border-radius:1rem}}
        .ol-x{position:absolute;top:1rem;right:1rem;z-index:10;width:2rem;height:2rem;border-radius:50%;background:rgba(255,255,255,.8);border:0;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#6b7280}
        .dark .ol-x{background:rgba(30,41,59,.8);color:#94a3b8}
        .ol-img{width:100%;height:14rem;background:color-mix(in srgb,var(--text) 5%,transparent);overflow:hidden;display:flex;align-items:center;justify-content:center}
        .ol-img img{width:100%;height:100%;object-fit:cover}
        .pb{display:inline-block;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;font-weight:700;padding:.25rem .75rem;border-radius:.5rem;font-size:.875rem}

        /* Divider */
        .dv{display:flex;align-items:center;gap:1rem;margin:2rem 0}
        .dv::before,.dv::after{content:'';flex:1;height:1px;background:var(--border)}
        .dv span{font-size:.8125rem;color:var(--text-muted);white-space:nowrap}

        /* Grid */
        .g2{display:grid;grid-template-columns:1fr;gap:.75rem}
        @media(min-width:640px){.g2{grid-template-columns:1fr 1fr}}

        /* Helpers */
        .tc{text-align:center}
        .ts{color:var(--text-sub)}.tm{color:var(--text-muted)}.tp{color:var(--primary)}
        .fs{font-size:.875rem}.fxs{font-size:.75rem}
        .fb{font-weight:700}.fs6{font-weight:600}
        .tr{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .dn{display:none}
        .rel{position:relative}
        .pwd-toggle{position:absolute;right:.75rem;top:50%;transform:translateY(-50%);color:var(--text-muted);background:0;border:0;cursor:pointer;display:flex;padding:0}
        .pwd-toggle:hover{color:var(--text-sub)}
    </style>
</head>
<body x-data="themeManager()" x-init="init()">

    <nav class="nav">
        <div class="nav-in">
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="nav-logo">
                <img src="/images/logoNoName.png" alt="MenuSnap">
                <b>MenuSnap</b>
            </a>
            <div class="nav-r">
                @auth
                    <a href="{{ route('settings') }}" class="nav-icon" title="Einstellungen">
                        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="nav-out">Abmelden</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link nav-link-p">Anmelden</a>
                    <a href="{{ route('register') }}" class="nav-btn">Registrieren</a>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="flash" x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,4000)">
            <div class="flash-s">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="flash" x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,4000)">
            <div class="flash-e">{{ session('error') }}</div>
        </div>
    @endif

    <main>@yield('content')</main>

    <footer class="f">
        <a href="{{ url('/') }}">
            <img src="/images/logoNoName.png" alt="MenuSnap">
            &copy; {{ date('Y') }} MenuSnap
        </a>
    </footer>

    <script>
        function themeManager() {
            return {
                isDark: false, mode: 'system',
                init() {
                    this.mode = localStorage.getItem('menusnap_theme') || 'system';
                    this.apply();
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => { if (this.mode === 'system') this.apply(); });
                },
                setMode(m) { this.mode = m; localStorage.setItem('menusnap_theme', m); this.apply(); },
                apply() {
                    this.isDark = this.mode === 'dark' ? true : this.mode === 'light' ? false : window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.getElementById('app-root').classList.toggle('dark', this.isDark);
                }
            };
        }
        document.addEventListener('alpine:init', () => {
            Alpine.store('recents', {
                items: JSON.parse(localStorage.getItem('menusnap_recents') || '[]'),
                add(r) {
                    const e = { id: r.id, name: r.name, address: r.address || '', openedAt: new Date().toISOString() };
                    this.items = [e, ...this.items.filter(x => x.id !== r.id)].slice(0, 20);
                    localStorage.setItem('menusnap_recents', JSON.stringify(this.items));
                },
                clearAll() { this.items = []; localStorage.removeItem('menusnap_recents'); },
                formatDate(d) {
                    const m = Math.floor((Date.now() - new Date(d).getTime()) / 60000);
                    if (m < 1) return 'Gerade eben'; if (m < 60) return 'Vor ' + m + 'm';
                    const h = Math.floor(m / 60); if (h < 24) return 'Vor ' + h + 'h';
                    const dy = Math.floor(h / 24); if (dy === 1) return 'Gestern'; if (dy < 7) return 'Vor ' + dy + ' Tagen';
                    return new Date(d).toLocaleDateString('de-DE');
                }
            });
        });
    </script>
</body>
</html>
