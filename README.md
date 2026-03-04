# MenuSnap

MenuSnap ist ein QR-Menü SaaS für Restaurants.
Restaurantbesitzer verwalten ihr Menü über eine Admin-App, während Gäste das Menü direkt im Browser sehen, nachdem sie einen QR-Code am Tisch scannen.

## Konzept

1. Gast scannt QR-Code am Tisch
2. Der QR-Code öffnet eine Menüseite im Browser
3. Das Menü wird direkt aus der Datenbank geladen und angezeigt

Keine App für Gäste nötig.

## Architektur

MenuSnap besteht aus zwei Hauptkomponenten:

* **Flutter Admin App** – Verwaltung für Restaurantbesitzer
* **Laravel Backend** – API, Datenbank und Gastseiten

## Repository Struktur

```
menusnap/
│
├── apps/
│   ├── backend/        # Laravel Backend (PHP-FPM, Vite/Tailwind)
│   └── admin_app/      # Flutter Admin App
│
├── docs/               # Projektdokumentation
│
├── infra/
│   ├── docker/
│   │   ├── php/
│   │   │   ├── Dockerfile      # PHP 8.4-FPM + alle Laravel Extensions
│   │   │   ├── php.ini         # PHP runtime config
│   │   │   └── entrypoint.sh   # composer install beim ersten Start
│   │   └── nginx/
│   │       └── default.conf    # Nginx vhost → PHP-FPM
│   └── scripts/
│       ├── up.sh               # docker compose up --build
│       ├── down.sh             # docker compose down
│       ├── rebuild.sh          # Rebuild ohne Cache
│       ├── logs.sh             # Container-Logs
│       ├── artisan.sh          # php artisan im Container
│       ├── composer.sh         # composer im Container
│       └── npm.sh              # npm im Node-Container
│
├── docker-compose.yml  # Services: php, nginx, mysql
├── README.md
└── .gitignore
```

---

## Docker – Schnellstart

> **Voraussetzung:** nur `docker` und `docker compose` auf dem Host.
> Kein PHP, Composer, Node oder MySQL nötig.

```bash
# 1. Umgebungsdatei vorbereiten (einmalig)
cp apps/backend/.env.example apps/backend/.env
# → APP_PORT und DB_EXTERNAL_PORT anpassen falls Port 80/3306 belegt ist

# 2. Alles starten (Images werden beim ersten Mal automatisch gebaut)
#    composer install läuft automatisch im Container beim ersten Start
docker compose up -d

# 3. App-Key setzen (einmalig)
./infra/scripts/artisan.sh key:generate

# 4. Frontend-Assets bauen (einmalig und nach CSS/JS-Änderungen)
./infra/scripts/npm.sh install   # Node-Module für Alpine installieren
./infra/scripts/npm.sh run build

# 5. Datenbank migrieren (einmalig)
./infra/scripts/artisan.sh migrate
```

→ **http://localhost** (oder `http://localhost:${APP_PORT}`) öffnet die MenuSnap Landing Page.

---

## Ports & URLs

| Service | Standard-Port  | Env-Variable      | Beschreibung              |
|---------|----------------|-------------------|---------------------------|
| Web     | 80             | `APP_PORT`        | Nginx → Laravel           |
| MySQL   | 3306           | `DB_EXTERNAL_PORT`| Direkt (Dev/DB-Clients)   |

Ports können in `apps/backend/.env` überschrieben werden, falls Konflikte bestehen.

---

## Infra Konfiguration

| Datei                               | Inhalt                               |
|-------------------------------------|--------------------------------------|
| `docker-compose.yml`                | Services: php, nginx, mysql          |
| `infra/docker/php/Dockerfile`       | PHP 8.4-FPM, alle Laravel Extensions |
| `infra/docker/php/php.ini`          | Memory, Upload-Limits, OPcache       |
| `infra/docker/php/entrypoint.sh`    | Auto-composer-install beim Start     |
| `infra/docker/nginx/default.conf`   | Nginx → fastcgi php:9000             |

---

## Docker – Tägliche Befehle

### Starten / Stoppen

```bash
./infra/scripts/up.sh          # starten (mit Build falls nötig)
./infra/scripts/down.sh        # stoppen (Volumes bleiben erhalten)
./infra/scripts/rebuild.sh     # alles neu bauen (kein Cache)
./infra/scripts/logs.sh        # alle Logs
./infra/scripts/logs.sh php    # nur PHP-FPM Logs
```

### Artisan

```bash
./infra/scripts/artisan.sh migrate
./infra/scripts/artisan.sh migrate --seed
./infra/scripts/artisan.sh cache:clear
./infra/scripts/artisan.sh route:list
./infra/scripts/artisan.sh tinker
```

### Composer

```bash
./infra/scripts/composer.sh install
./infra/scripts/composer.sh update
./infra/scripts/composer.sh require vendor/package
```

### NPM / Vite

```bash
./infra/scripts/npm.sh install          # Pakete installieren (Alpine-kompatibel)
./infra/scripts/npm.sh run build        # Produktions-Build
./infra/scripts/npm.sh run dev          # Vite Dev-Server (Port 5173)
```

### Shell im Container

```bash
docker compose exec php sh
docker compose exec mysql mysql -u menusnap -psecret menusnap
```

---

## Backend

Das Backend basiert auf **Laravel** und stellt folgende Funktionen bereit:

* REST API für die Admin-App
* Authentifizierung
* Verwaltung von Restaurants, Menüs und Gerichten
* Gastseiten für QR-Menüs

## Admin App

Die Admin-App wird mit **Flutter** entwickelt und ermöglicht:

* Login / Registrierung
* Restaurantprofil verwalten
* Menü erstellen, Kategorien und Gerichte verwalten
* Tische erstellen, QR-Codes anzeigen und exportieren
* Abonnement verwalten

## Tech Stack

| Schicht    | Technologie                          |
|------------|--------------------------------------|
| Backend    | Laravel 12, PHP 8.4                  |
| Datenbank  | MySQL 8.4                            |
| Frontend   | Blade, Tailwind CSS v4, Vite 7       |
| Webserver  | Nginx 1.27 (Reverse Proxy)           |
| Laufzeit   | Docker / Docker Compose              |
| Admin App  | Flutter                              |

## Monetarisierung

MenuSnap verwendet ein Freemium-Modell (Free / Pro).
Bezahlung über Apple App Store und Google Play In-App Subscriptions.

## Design – Farbpalette (Landing Page)

### Kernfarben

| Rolle | Tailwind-Klasse | Hex |
|---|---|---|
| Primär | `indigo-600` | `#4f46e5` |
| Akzent | `violet-600` | `#7c3aed` |
| Positiv (Checks) | `emerald-400` | `#34d399` |

### Hero-Hintergrund (Verlauf `from → via → to`)

| Stop | Tailwind-Klasse | Hex |
|---|---|---|
| Start | `indigo-950` | `#1e1b4b` |
| Mitte | `indigo-800` | `#3730a3` |
| Ende | `violet-700` | `#6d28d9` |

### Hero-Dekor & Texte

| Rolle | Tailwind-Klasse | Hex / Opacity |
|---|---|---|
| Blur-Kreis links | `indigo-500/20` | `#6366f1` @ 20% |
| Blur-Kreis rechts | `violet-500/20` | `#8b5cf6` @ 20% |
| Titel (weiss) | `white` | `#ffffff` |
| Fliesstext | `white/70` | `#ffffff` @ 70% |
| Gedimmte Pills | `white/60` | `#ffffff` @ 60% |
| Akzent-Headline | `violet-300` | `#c4b5fd` |

### Buttons

| Rolle | Tailwind-Klasse | Hex |
|---|---|---|
| Primär BG | `white` | `#ffffff` |
| Primär Text | `indigo-700` | `#4338ca` |
| Sekundär BG | `white/10` | `#ffffff` @ 10% |
| Sekundär Hover | `white/20` | `#ffffff` @ 20% |

### Sections & Cards

| Rolle | Tailwind-Klasse | Hex |
|---|---|---|
| Section BG (grau) | `gray-50` | `#f9fafb` |
| Section BG (weiss) | `white` | `#ffffff` |
| Überschriften | `gray-900` | `#111827` |
| Fliesstext | `gray-500` | `#6b7280` |
| Label-Farbe | `indigo-600` | `#4f46e5` |
| Card-Border | `gray-100` | `#f3f4f6` |
| Card-Hover Border | `indigo-100` | `#e0e7ff` |
| Card-Hover BG | `indigo-50/30` | `#eef2ff` @ 30% |

### CTA-Banner (Verlauf)

| Stop | Tailwind-Klasse | Hex |
|---|---|---|
| Links | `indigo-600` | `#4f46e5` |
| Rechts | `violet-600` | `#7c3aed` |

### Footer

| Rolle | Tailwind-Klasse | Hex |
|---|---|---|
| Text | `gray-400` | `#9ca3af` |
| Border | `gray-100` | `#f3f4f6` |

---

## Status

Projekt in Entwicklung.
