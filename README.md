# TA Private - Waste Bank (Bank Sampah) System

## 1. Project Overview & Business Logic

**Primary Purpose:**
TA Private is a "Bank Sampah" (Waste Bank) management application that seamlessly connects households (Nasabah) with waste administrators/collectors (Admins/Pelaku Usaha). The platform incentivizes proper waste disposal and recycling by gamifying the process through a point-based rewards system.

**Core Features & User Flows:**
* **Nasabah (Standard Users):** Users can register, monitor waste catalogs, and request waste pickups (`Penjemputan`) indicating the type, amount, and location of the waste. They can monitor their point balance and generate withdrawal requests (`Penarikan Poin`) representing cash or other rewards.
* **Admins/Pelaku Usaha:** An administrative role that governs the master data for waste types (`Jenis Sampah`). Crucially, they manage the transaction queue, assessing pending waste pick-ups and approving them (`disetujui`) or rejecting them. 
* **Points Ledger:** Once an admin approves a transaction, a specified number of points based on the waste volume and type is automatically allocated to the Nasabah's global total and logged permanently in a receipt ledger (`Poin`).

**Specific Business Rules:**
* **Role Constraints:** RBAC is enforced on the route level (`role:admin` middleware). Standard users only see their individual transaction histories and point balances.
* **Transaction Lifecycle:** New transactions default to a `pending` state. Points (`Poin`) are explicitly strictly linked to a `transaksi_id` and are only minted securely via backend DB transactions when an Admin flips the state to `disetujui` and defines the point reward limit.
* **Backward-Compatible Roles:** "Pelaku Usaha" was recently refactored into the core "Admin" architecture dynamically within the `hasAnyRole` check in the `User` model, mapping old database states continuously into the new application UI.

---

## 2. Tech Stack & Dependencies

**Backend Environment:**
* **Framework:** Laravel 11.31 (PHP 8.2)
* **Authentication/Security:** Laravel Jetstream (v5.3), Laravel Sanctum (v4.0), Laravel Fortify (Provides dual session/API token guards plus 2FA schemas).
* **Payment Integration:** Midtrans-PHP (v2.6) - Integrated for external payments or converting points to cash equivalents structurally out-of-the-box.
* **Livewire:** Version 3.0 configured for reactive Jetstream components profile management.

**Database:**
* **RDBMS:** MySQL 8 containerized locally 

**Frontend Environment:**
* **Build System:** Vite (Node 20 environment)
* **Styling Frameworks:** Tailwind CSS (v3.4.0) alongside Bootstrap 5.3.3.
* **Plugins:** `@tailwindcss/forms` & `@tailwindcss/typography` enforcing Laravel's baseline typography.

---

## 3. Data Flow & Architecture

**Request Lifecycle:**
1. incoming requests reach Nginx and are proxy-passed statically to the Dockerized core `app` (PHP-FPM).
2. `public/index.php` loads Laravel. `app/Http/Kernel` applies Jetstream sessions and CSRF guards.
3. Accesses to `/penjemputan` hit the `TransaksiController`; admin changes directly utilize sub-namespaced `/admin/*` routes targeting controllers like `AdminTransaksiController`.

**Routing Structure:**
* `routes/web.php`: Manages all primary interactions (guest redirects, dashboard, transactions). Wrapped tightly around custom `role:admin` guards mapped inside HTTP kernel overrides. 
* `routes/api.php`: Primarily configured for basic `auth:sanctum` user extraction, opening doors for prospective mobile microservices.

**Database Schema & Relationships:**
* `users`: Core identity table extending authenticatable schemas. Encompasses `total_poin`.
* `roles` & `user_roles`: A pivot mapping standard users to roles like "admin".
* `jenis_sampah`: Master inventory representing acceptable recycle variants.
* `transaksi`: (1-to-many from Users, 1-to-many from Jenis Sampah) represents the core temporal request.
* `poin`: Immutable ledger table linked explicitly to `transaksi_id` and `nasabah_id` guaranteeing auditability of all points granted.
* `penarikan_poin`: Standalone relational ledger for outgoing point burns.

---

## 4. Project Scope & Directory Structure

```text
app/
├── Actions/                  # Jetstream & Fortify automated single-action classes (e.g. UpdateUserPassword)
├── Http/
│   └── Controllers/ 
│       ├── Admin/            # Admin segmented logic (AdminTransaksiController, AdminKatalogController)
│       └── ...               # Nasabah logic (TransaksiController, PoinController, PenarikanPoinController)
├── Models/                   # Centralized Eloquent Models establishing logic graphs (appended with advanced attributes like Appends, Casts)
├── Providers/                # Service container bootstraps
database/
├── migrations/               # Sequenced up/down methods tracking DB evolutions (roles merging, timestamps)
├── seeders/                  # Local state generation (AdminSeeder, Users, JenisSampah)
docker/                       # Raw container configs alongside entrypoint shell injections
nginx/
└── default.conf              # High-performance Nginx server blocks
resources/                    # Uncompiled blade templates and raw Vite JS/CSS assets
routes/                       # Web & API definition vectors
```
* **Architectural Flow Note:** The project relies primarily on the traditional "Fat-Model / Thin-Controller" variant of MVC architecture, supplemented by Fortify's `Action` pipeline exclusively for auth changes, ensuring main enterprise features stay within domain representations.

---

## 5. Installation & Setup

This project runs with Docker for local development.

### Requirements

- Docker Desktop (macOS/Windows/Linux)
- Docker Compose v2 (included in Docker Desktop)
- Git

> macOS: enable VirtioFS in Docker Desktop for better bind mount performance.

### Windows (Laragon) Quick Notes

- Stop Laragon services (Apache/Nginx/MySQL) first to avoid port conflicts.
- Use PowerShell equivalent commands:

```powershell
Copy-Item .env.example .env
```

- `WWWUSER` / `WWWGROUP` are mainly for Unix/macOS ownership mapping. On Windows + Docker Desktop, you can keep defaults from `docker-compose.yml`.
- If using WSL2 for development, run the macOS/Linux UID/GID step from inside WSL:

```bash
echo "WWWUSER=$(id -u)" >> .env
echo "WWWGROUP=$(id -g)" >> .env
```

### Quick Start

1. Clone repository

```bash
git clone <your-repo-url>
cd ta-private
```

2. Create `.env`

```bash
cp .env.example .env
```

3. Set local UID/GID (important so generated files are not owned by root)

```bash
echo "WWWUSER=$(id -u)" >> .env
echo "WWWGROUP=$(id -g)" >> .env
```

4. Update `.env` database + app URL values

```dotenv
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=bank_sampah
DB_USERNAME=laravel
DB_PASSWORD=<your_database_password>

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Used by docker-compose for MySQL root account
DB_ROOT_PASSWORD=<your_root_password>
```

5. Build image + start core services

```bash
docker compose up -d --build app web db phpmyadmin
```

6. Install PHP dependencies (first run)

```bash
docker compose exec app composer install
```

7. Generate key, migrate, lalu seed data dulu

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

> Untuk setup baru, lebih aman pakai satu perintah ini agar tabel + data dummy langsung siap:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

8. Run frontend dev server (recommended before opening app)

```bash
docker compose up -d node
docker compose logs -f node
```

Wait until Vite logs show `VITE ... ready`, then open the app.

### Access URLs

- App: http://localhost:8000
- phpMyAdmin: http://localhost:8080
	- Server: `db`
	- Username: `laravel`
	- Password: `<your_database_password>`
- Vite: http://localhost:5173

### Useful Commands

```bash
# Start all services
docker compose up -d

# Stop all services
docker compose down

# Stop and remove volumes (WARNING: delete MySQL data)
docker compose down -v

# Open app container shell
docker compose exec app bash

# Run Artisan command
docker compose exec app php artisan <command>

# Restart PHP + Nginx (quick fix if rendering is odd)
docker compose restart app web

# Start/stop only Vite
docker compose up -d node
docker compose stop node
```

### Common Issues

- `docker compose up --build` fails right away:
	- Ensure Docker Desktop is running.
	- Ensure `.env` exists.
	- Check logs: `docker compose logs --tail=200`.

- Permission denied on `storage` or `bootstrap/cache`:
	- Ensure `WWWUSER` and `WWWGROUP` are set in `.env`.
	- Rebuild app image: `docker compose build --no-cache app`.

- Frontend hot reload not detecting file changes on macOS:
	- Vite polling is already enabled in `docker-compose.yml` for the `node` service.

- Accidentally ran `php artisan serve` in container:
	- Do not use it in this stack (we already use Nginx + PHP-FPM).
	- Recover with:

```bash
docker compose restart app web
docker compose up -d node
```

### Notes

- MySQL data is persistent in named volume `db_data`.
- Database `bank_sampah` is created automatically on first MySQL initialization.
- Seeder sudah dipisah per model (`User`, `JenisSampah`, `PelakuUsaha`, `Transaksi`, `Poin`, `PenarikanPoin`). Jalankan seed sebelum testing fitur agar data relasi terisi.
