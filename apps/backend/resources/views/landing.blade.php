<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MenuSnap – Digitale QR-Speisekarten für Restaurants</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- ───────────────────────────── HERO ───────────────────────────── --}}
    <section class="relative min-h-screen flex flex-col items-center justify-center px-6 text-center overflow-hidden">

        {{-- Hintergrund: Farbverlauf + dekorative Kreise --}}
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-indigo-800 to-violet-700 -z-10"></div>
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] rounded-full bg-indigo-500/20 blur-3xl -z-10"></div>
        <div class="absolute -bottom-40 -right-20 w-[600px] h-[600px] rounded-full bg-violet-500/20 blur-3xl -z-10"></div>
        {{-- Dezentes Gitter-Muster --}}
        <div class="absolute inset-0 opacity-10 -z-10"
             style="background-image: linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px),
                                      linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
                    background-size: 40px 40px;"></div>

        {{-- Navbar mit Logo --}}
        <nav class="absolute top-0 left-0 right-0 flex items-center justify-between px-8 py-5 z-10">
            <img src="/images/logoWithName.png" alt="MenuSnap" class="h-10 w-auto" style="filter: brightness(0) invert(1);">
            <a href="#"
               class="bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-4 py-2 rounded-lg backdrop-blur-sm transition-colors duration-150">
                Anmelden
            </a>
        </nav>

        <div class="max-w-3xl w-full">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white/80 text-sm font-medium px-4 py-1.5 rounded-full mb-8 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Jetzt kostenlos starten
            </div>

            {{-- Headline --}}
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-tight mb-6">
                Digitale Speisekarte.<br>
                <span class="text-violet-300">QR-Code scannen.</span><br>
                Fertig.
            </h1>

            <p class="text-lg sm:text-xl text-white/70 mb-6 max-w-xl mx-auto leading-relaxed">
                Erstelle in Minuten eine ansprechende digitale Speisekarte für dein Restaurant.
                Gäste scannen einfach den QR-Code am Tisch — keine App, kein Download.
            </p>

            {{-- Feature-Pills --}}
            <div class="flex flex-wrap justify-center gap-3 mb-10 text-sm text-white/60">
                <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Keine App nötig</span>
                <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Menü jederzeit anpassbar</span>
                <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Funktioniert auf allen Geräten</span>
            </div>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#"
                   class="inline-block bg-white hover:bg-gray-100 text-indigo-700 font-semibold text-base px-8 py-3.5 rounded-xl shadow-lg transition-colors duration-150">
                    Kostenlos starten
                </a>
                <a href="#wie-es-funktioniert"
                   class="inline-block bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-base px-8 py-3.5 rounded-xl transition-colors duration-150 backdrop-blur-sm">
                    Mehr erfahren
                </a>
            </div>
        </div>

        {{-- Scroll-Pfeil --}}
        <a href="#wie-es-funktioniert" class="absolute bottom-8 text-white/40 hover:text-white/80 transition-colors">
            <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </a>
    </section>

    {{-- ─────────────────────── WIE ES FUNKTIONIERT ─────────────────────── --}}
    <section id="wie-es-funktioniert" class="bg-gray-50 py-24 px-6">
        <div class="max-w-4xl mx-auto">
            <p class="text-indigo-600 font-semibold text-sm uppercase tracking-widest text-center mb-3">So einfach geht's</p>
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-16">In drei Schritten live</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-10">

                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl mb-5 shadow-md">
                        📱
                    </div>
                    <h3 class="font-semibold text-gray-900 text-lg mb-2">QR-Code scannen</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Der Gast hält einfach die Kamera seines Smartphones über den QR-Code am Tisch — fertig.
                    </p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl mb-5 shadow-md">
                        🍽️
                    </div>
                    <h3 class="font-semibold text-gray-900 text-lg mb-2">Speisekarte sofort sehen</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Das Menü lädt direkt im Browser — keine App, kein Login, keine Wartezeit.
                    </p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl mb-5 shadow-md">
                        ✏️
                    </div>
                    <h3 class="font-semibold text-gray-900 text-lg mb-2">Menü jederzeit anpassen</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Gerichte, Preise und Kategorien kannst du jederzeit direkt in der Admin-App aktualisieren.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ───────────────────────────── VORTEILE ───────────────────────────── --}}
    <section class="py-24 px-6 bg-white">
        <div class="max-w-4xl mx-auto">
            <p class="text-indigo-600 font-semibold text-sm uppercase tracking-widest text-center mb-3">Warum MenuSnap?</p>
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-16">Alles, was dein Restaurant braucht</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="flex gap-4 p-6 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-colors">
                    <div class="text-2xl shrink-0">⚡</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">In Minuten startklar</h3>
                        <p class="text-sm text-gray-500">Kein technisches Know-how nötig. Menü anlegen, QR-Code drucken, fertig.</p>
                    </div>
                </div>

                <div class="flex gap-4 p-6 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-colors">
                    <div class="text-2xl shrink-0">🔄</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Echtzeit-Updates</h3>
                        <p class="text-sm text-gray-500">Preise oder Tageskarte geändert? Die Gäste sehen die Änderung sofort.</p>
                    </div>
                </div>

                <div class="flex gap-4 p-6 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-colors">
                    <div class="text-2xl shrink-0">📲</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Keine App für Gäste</h3>
                        <p class="text-sm text-gray-500">Funktioniert auf jedem Smartphone direkt im Browser — iOS und Android.</p>
                    </div>
                </div>

                <div class="flex gap-4 p-6 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-colors">
                    <div class="text-2xl shrink-0">🌐</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Mehrsprachig</h3>
                        <p class="text-sm text-gray-500">Speisekarte in mehreren Sprachen anbieten — ideal für internationale Gäste.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ───────────────────────────── CTA BANNER ───────────────────────────── --}}
    <section class="relative py-20 px-6 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 -z-10"></div>
        <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-white/5 blur-2xl -z-10"></div>
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Bereit, loszulegen?</h2>
            <p class="text-white/70 text-lg mb-8">
                Starte noch heute kostenlos und bring deine Speisekarte ins digitale Zeitalter.
            </p>
            <a href="#"
               class="inline-block bg-white hover:bg-gray-100 text-indigo-700 font-semibold text-base px-10 py-4 rounded-xl shadow-lg transition-colors duration-150">
                Jetzt kostenlos registrieren
            </a>
        </div>
    </section>

    {{-- ───────────────────────────── FOOTER ───────────────────────────── --}}
    <footer class="py-8 bg-white border-t border-gray-100">
        <div class="flex flex-col items-center gap-3">
            <img src="/images/logoNoName.png" alt="MenuSnap" class="h-5 w-auto opacity-50">
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} MenuSnap. Alle Rechte vorbehalten.</p>
        </div>
    </footer>

</body>
</html>
