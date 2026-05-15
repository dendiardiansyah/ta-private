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

---

## 6. Instalasi Tanpa Docker (Windows/Laragon atau macOS/Linux Native)

Jika Anda ingin menjalankan project ini tanpa menggunakan Docker, ikuti panduan berikut sesuai dengan sistem operasi Anda.

### Persyaratan Umum

**Untuk semua platform:**
- PHP 8.2 atau lebih tinggi
- Composer (PHP dependency manager)
- MySQL 8.0 atau MariaDB 10.4+
- Node.js 20+ (untuk Vite frontend)
- Git

**Versi PHP Extensions yang diperlukan:**
- `pdo` dan `pdo_mysql`
- `mbstring`
- `exif`
- `pcntl`
- `bcmath`
- `gd`
- `zip`
- `intl`
- `opcache`
- `xml`

---

### Setup di Windows dengan Laragon

**Langkah 1: Install Prerequisites**

1. **Laragon** (https://laragon.org/)
   - Download dan install Laragon (includes Apache, MySQL, Node.js)
   - Pastikan MySQL dan Apache tidak auto-start (atur di Laragon settings)
   - Tempat default: `C:\laragon`

2. **Git** (https://git-scm.com/)

3. **Composer** (https://getcomposer.org/)
   - Download standalone installer
   - Pastikan bisa diakses dari command line: `composer --version`

**Langkah 2: Clone Repository**

```powershell
# Pilih lokasi development (misal Documents atau C:\projects)
cd C:\projects
git clone <your-repo-url>
cd ta-private
```

**Langkah 3: Configure PHP di Laragon**

1. Buka Laragon, klik **Menu > PHP > Version**
2. Pilih PHP 8.2 atau lebih tinggi

3. Pastikan `php.ini` memiliki ekstensi yang diperlukan:
   ```
   extension=pdo_mysql
   extension=mbstring
   extension=gd
   extension=zip
   extension=intl
   extension=exif
   extension=bcmath
   extension=opcache
   ```
   Edit file: `C:\laragon\etc\php\php.ini`

**Langkah 4: Setup Environment**

```powershell
# Copy .env.example ke .env
Copy-Item .env.example .env

# Edit .env dengan text editor (Notepad/VS Code)
# Konfigurasi database:
$env:DB_CONNECTION = "mysql"
$env:DB_HOST = "127.0.0.1"
$env:DB_PORT = "3306"
$env:DB_DATABASE = "bank_sampah"
$env:DB_USERNAME = "root"
$env:DB_PASSWORD = ""

# APP_URL harus sesuai dengan domain di Laragon
$env:APP_URL = "http://ta-private.test"
```

**Langkah 5: Setup Database di MySQL**

1. Buka Laragon, klik **Database** atau akses phpMyAdmin di `http://localhost/phpmyadmin`
2. Buat database baru: `bank_sampah`
   ```sql
   CREATE DATABASE bank_sampah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

**Langkah 6: Configure Laragon Virtual Host**

1. Edit file: `C:\laragon\etc\apache2\sites-enabled\ta-private.test.conf`
   (jika belum ada, copy dari template lain atau buat baru)

   ```apache
   <VirtualHost *:80>
       ServerName ta-private.test
       ServerAlias *.ta-private.test
       DocumentRoot "C:\laragon\www\ta-private\public"
       
       <Directory "C:\laragon\www\ta-private\public">
           AllowOverride All
           Require all granted
           
           <IfModule mod_rewrite.c>
               RewriteEngine On
               RewriteCond %{REQUEST_FILENAME} !-d
               RewriteCond %{REQUEST_FILENAME} !-f
               RewriteRule ^ index.php [QSA,L]
           </IfModule>
       </Directory>
   </VirtualHost>
   ```

2. Edit `C:\Windows\System32\drivers\etc\hosts` (gunakan Notepad as Administrator):
   ```
   127.0.0.1       ta-private.test
   127.0.0.1       localhost
   ```

3. Restart Apache di Laragon

**Langkah 7: Install PHP Dependencies**

```powershell
cd C:\projects\ta-private
composer install
```

**Langkah 8: Generate Application Key**

```powershell
php artisan key:generate
```

**Langkah 9: Jalankan Migrations & Seeders**

```powershell
# Jalankan semua migrations
php artisan migrate

# Atau fresh migration + seed (recommended untuk setup baru)
php artisan migrate:fresh --seed
```

**Langkah 10: Install & Setup Frontend**

```powershell
# Install Node dependencies
npm install

# Jalankan Vite dev server
npm run dev
```

**Langkah 11: Akses Application**

- **App:** http://ta-private.test:8000
- **Vite Dev Server:** http://localhost:5173 (untuk hot reload)

> Catatan: Ganti port jika ada konflik. Default Apache di Laragon adalah port 80.

---

### Setup di macOS Native

**Langkah 1: Install Prerequisites via Homebrew**

```bash
# Install Homebrew jika belum ada
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP 8.2
brew install php@8.2
brew link php@8.2

# Install MySQL
brew install mysql

# Install Composer
brew install composer

# Install Node.js
brew install node@20
brew link node@20
```

**Langkah 2: Configure MySQL**

```bash
# Start MySQL service
brew services start mysql

# Setup MySQL root password
mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'your_password';"

# Create database
mysql -u root -p -e "CREATE DATABASE bank_sampah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Langkah 3: Clone Repository & Install Dependencies**

```bash
git clone <your-repo-url>
cd ta-private

# Copy .env
cp .env.example .env

# Update .env
cat > .env << EOF
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bank_sampah
DB_USERNAME=root
DB_PASSWORD=your_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
EOF

# Install PHP dependencies
composer install

# Generate app key
php artisan key:generate
```

**Langkah 4: Database Setup**

```bash
# Run migrations
php artisan migrate:fresh --seed
```

**Langkah 5: Configure Local Development Server**

**Option A: Menggunakan PHP Built-in Server**
```bash
# Terminal 1: Jalankan Laravel server (port 8000)
php artisan serve

# Terminal 2: Jalankan Vite dev server
npm install
npm run dev
```

**Option B: Menggunakan Nginx (Recommended)**

```bash
# Install Nginx
brew install nginx

# Start Nginx
brew services start nginx

# Create Nginx config di /opt/homebrew/etc/nginx/servers/ta-private.conf
cat > /opt/homebrew/etc/nginx/servers/ta-private.conf << 'EOF'
server {
    listen 8000;
    server_name localhost;
    root /path/to/ta-private/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

# Start PHP-FPM
php-fpm

# Restart Nginx
brew services restart nginx
```

**Langkah 6: Akses Application**

- **App:** http://localhost:8000
- **Vite Dev Server:** http://localhost:5173

---

### Setup di Linux (Ubuntu/Debian)

**Langkah 1: Install Prerequisites**

```bash
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-mbstring \
  php8.2-gd php8.2-zip php8.2-intl php8.2-exif php8.2-bcmath php8.2-xml \
  php8.2-opcache mysql-server mysql-client composer nodejs npm nginx
```

**Langkah 2: Clone & Install**

```bash
git clone <your-repo-url>
cd ta-private
cp .env.example .env

# Update .env sesuai konfigurasi
nano .env

# Install dependencies
composer install
npm install

# Generate key
php artisan key:generate
```

**Langkah 3: Database Setup**

```bash
sudo systemctl start mysql
sudo mysql -u root -e "CREATE DATABASE bank_sampah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate:fresh --seed
```

**Langkah 4: Configure PHP-FPM & Nginx**

```bash
# Start services
sudo systemctl start php8.2-fpm
sudo systemctl start nginx

# Create Nginx config
sudo tee /etc/nginx/sites-available/ta-private > /dev/null << 'EOF'
server {
    listen 8000;
    server_name _;
    root /path/to/ta-private/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

# Enable site
sudo ln -s /etc/nginx/sites-available/ta-private /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

**Langkah 5: Jalankan Frontend Dev Server (Terminal Terpisah)**

```bash
npm run dev
```

**Langkah 6: Akses Application**

- **App:** http://localhost:8000
- **Vite Dev Server:** http://localhost:5173

---

### Common Issues (Non-Docker Setup)

| Issue | Solusi |
|-------|--------|
| `SQLSTATE[HY000]: General error` saat migrate | Pastikan MySQL sudah running dan database sudah dibuat |
| Port 8000 sudah terpakai | Ganti dengan port lain: `php artisan serve --port=8001` |
| File permissions error di `storage/` atau `bootstrap/cache/` | Run: `chmod -R 775 storage bootstrap/cache` |
| Vite tidak detect file changes | Cek apakah npm run dev masih running |
| Memory limit exceeded saat `composer install` | Increase PHP memory: `php -d memory_limit=-1 $(which composer) install` |
| `bcmath` extension not found | Install: `sudo apt install php8.2-bcmath` (Linux) atau aktifkan di php.ini |

---

### Production Deployment Notes

Untuk production, berikut tips penting:

1. **Update `.env` untuk production:**
   ```dotenv
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=<generated-key>
   
   # Use Redis/Memcached jika tersedia
   CACHE_STORE=redis
   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   ```

2. **Jalankan optimization commands:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize:clear
   ```

3. **Setup proper web server:**
   - Gunakan Nginx atau Apache dengan SSL/TLS
   - Set document root ke `public/` folder saja
   - Ensure `storage/` tidak accessible dari public

4. **Database backups:**
   ```bash
   # Backup MySQL
   mysqldump -u root -p bank_sampah > backup.sql
   
   # Restore dari backup
   mysql -u root -p bank_sampah < backup.sql
   ```

5. **Monitoring & Logging:**
   - Monitor `storage/logs/laravel.log`
   - Setup error tracking (Sentry, Bugsnag, dll)
