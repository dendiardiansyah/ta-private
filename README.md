# TA Private - Waste Bank (Bank Sampah) System

## 1. Project Overview & Business Logic

**Primary Purpose:**
TA Private is a "Bank Sampah" (Waste Bank) management application that connects households (Nasabah) with waste management personnel and business owners. The platform incentivizes proper waste disposal and recycling through a point-based rewards system that can be redeemed for products.

**Core Features & User Flows:**

### **Role-Based Features:**

#### **1. Nasabah (Standard Users)**
* Register and wait for admin approval
* Request waste pickups (`Penjemputan`) by specifying waste type, amount, and pickup location
* Monitor pickup history and status updates
* View point balance earned from waste collection
* Purchase products from marketplace using accumulated points
* Submit point withdrawal requests for cash conversion (`Penarikan Poin`)

#### **2. Admin (System Administrators)**
* Approve or reject user registrations (Nasabah, Petugas, Pelaku Usaha)
* Manage master data for waste types (`Jenis Sampah`) - CRUD operations
* Manage user accounts and roles
* Configure point conversion rate (Rupiah per point)
* Review and approve/reject point withdrawal requests
* Monitor system-wide transactions and analytics

#### **3. Petugas (Field Workers)**
* View assigned waste pickup tasks
* Update pickup status in real-time:
  - Menunggu Petugas (Waiting)
  - Menuju Lokasi (En Route)
  - Sedang Diangkut (Collecting)
  - Selesai (Completed)
* Automatic point allocation when marking pickup as "Selesai"

#### **4. Pelaku Usaha (Business Owners)**
* Manage product catalog (CRUD operations)
* Set product pricing in Rupiah
* Manage inventory stock levels
* View sales dashboard and order history
* Products are sold via point redemption system

**Specific Business Rules:**

* **User Registration & Approval:** 
  - All new users with roles beyond standard Nasabah require admin approval
  - User status: `pending` → `approved` or `rejected`
  - Email notifications sent automatically on approval/rejection

* **Role Constraints:** 
  - RBAC enforced via middleware (`role:admin`, `role:petugas`, `role:pelaku_usaha`)
  - Standard users (Nasabah) only see their own transaction histories and point balances
  - Backward-compatible role aliasing: "Nasabah" ↔ "User" role names are interchangeable

* **Transaction Lifecycle:**
  1. Nasabah submits pickup request → Status: `Menunggu Petugas`
  2. Admin assigns Petugas → Petugas receives notification
  3. Petugas updates status through workflow
  4. When Petugas marks as `Selesai` → Automatic point calculation and allocation
  5. Points formula: `ceil(jumlah_kg × harga_sampah_per_kg)`
  6. Points added to Nasabah's `total_poin` and logged in immutable `poin` ledger

* **Points System:**
  - Points linked explicitly to `transaksi_id` for audit trail
  - Immutable ledger (`poin` table) maintains permanent record
  - Points can be withdrawn as cash (via `Penarikan Poin` - requires admin approval)
  - Points can be spent on products in marketplace (automatic conversion)
  - Conversion rate configurable by admin (default: 1 point = Rp 1,000)

* **Marketplace System:**
  - Pelaku Usaha sets product prices in Rupiah
  - System converts Rupiah to points using configured rate
  - On purchase: Stock decremented, points deducted via DB transaction
  - Order status tracked in `product_orders` table

---

## 2. Tech Stack & Dependencies

**Backend Environment:**
* **Framework:** Laravel 11.31 (PHP 8.2)
* **Authentication/Security:** Laravel Jetstream (v5.3), Laravel Sanctum (v4.0), Laravel Fortify (session guards + 2FA support)
* **Payment Integration:** Midtrans-PHP (v2.6) - Available for future payment gateway integration
* **Livewire:** Version 3.0 for reactive Jetstream profile management components
* **Concurrency:** Uses Laravel's built-in DB transactions with pessimistic locking for point operations

**Database:**
* **RDBMS:** MySQL 8.0 (containerized via Docker)
* **Key Tables:**
  - `users` - Core user identity with `total_poin` and `status` fields
  - `roles`, `user_roles` - RBAC pivot table structure
  - `jenis_sampah` - Waste type master data
  - `transaksi` - Pickup requests and transactions
  - `poin` - Immutable point allocation ledger
  - `penarikan_poin` - Point withdrawal requests
  - `products` - Marketplace product catalog
  - `product_orders` - Purchase transaction records
  - `settings` - System configuration (point rates, etc.)

**Frontend Environment:**
* **Build System:** Vite 5 (Node 20 environment)
* **Styling Frameworks:** Tailwind CSS (v3.4.0) + Bootstrap 5.3.3
* **Plugins:** `@tailwindcss/forms`, `@tailwindcss/typography`
* **Hot Module Replacement:** Polling enabled for Docker compatibility

---

## 3. Data Flow & Architecture

**Request Lifecycle:**
1. Incoming HTTP requests reach the Docker container running PHP's built-in development server (`php artisan serve`)
2. `public/index.php` bootstraps Laravel application
3. `bootstrap/app.php` loads Jetstream authentication and CSRF middleware
4. Requests route through `routes/web.php` with role-based middleware protection
5. Controllers process business logic with Eloquent ORM
6. DB transactions ensure data consistency for critical operations (point allocation, purchases)

**Routing Structure:**
* **Guest Routes:** `/` (welcome), `/login`, `/register` - redirect authenticated users
* **Authenticated Nasabah Routes:** 
  - `/dashboard` - News feed and home
  - `/penjemputan` - Request waste pickup
  - `/penjemputan/history` - View pickup history
  - `/poin` - View point balance and ledger
  - `/penarikan-poin` - Submit cash withdrawal request
  - `/katalog` - Browse products (public)
  - `/katalog/products/{id}/buy` - Purchase with points

* **Admin Routes (`/admin/*`)** - Protected by `role:admin` middleware:
  - `/admin/dashboard` - System overview
  - `/admin/jenis-sampah` - Waste type CRUD
  - `/admin/approvals` - User registration approvals
  - `/admin/users` - User management
  - `/admin/penarikan-poin` - Review withdrawal requests
  - `/admin/settings/point-rate` - Configure point conversion rate

* **Petugas Routes (`/petugas/*`)** - Protected by `role:petugas` middleware:
  - `/petugas` - View assigned pickup tasks
  - `/petugas/{id}` - Update pickup status

* **Pelaku Usaha Routes (`/pelaku-usaha/*`)** - Protected by `role:pelaku_usaha` middleware:
  - `/pelaku-usaha/dashboard` - Sales overview
  - `/pelaku-usaha/products` - Product CRUD operations

* **API Routes (`/api/*`)** - Protected by `auth:sanctum` middleware:
  - `/api/user` - Get authenticated user (foundation for future mobile API)

* **Fallback Route:** Intelligently redirects to role-appropriate dashboard or login

**Database Schema & Relationships:**
* `users` (1) ↔ (N) `user_roles` ↔ (N) `roles` - Many-to-many RBAC
* `users` (1) ↔ (N) `transaksi` - User's pickup requests (as `nasabah_id`)
* `users` (1) ↔ (N) `transaksi` - Petugas assignments (as `petugas_id`)
* `users` (1) ↔ (N) `poin` - Point allocation ledger
* `users` (1) ↔ (N) `penarikan_poin` - Withdrawal requests
* `users` (1) ↔ (1) `pelaku_usaha_profiles` - Business owner profile data
* `users` (1) ↔ (N) `product_orders` - Purchase history
* `jenis_sampah` (1) ↔ (N) `transaksi` - Waste type classification
* `transaksi` (1) ↔ (N) `poin` - Audit trail for point awards
* `products` (1) ↔ (N) `product_orders` - Order line items
* `products` (N) ↔ (1) `users` - Product ownership (pelaku_usaha_id)

**Key Model Methods:**
* `User::hasAnyRole(array $roles)` - Check if user has any of given roles
* `User::hasRole(string $role)` - Check single role
* `Setting::pointRateRupiahPerPoint()` - Get current point conversion rate
* `Transaksi::user()` - Get Nasabah relationship
* `Transaksi::petugas()` - Get assigned field worker
* `Poin::transaksi()` - Link to source transaction

---

## 4. Project Scope & Directory Structure

```text
app/
├── Actions/
│   ├── Fortify/              # Single-action classes for auth operations
│   │   ├── CreateNewUser.php
│   │   ├── UpdateUserPassword.php
│   │   └── ...
│   └── Jetstream/            # User deletion handler
├── Http/
│   ├── Controllers/
│   │   ├── Admin/            # Admin-specific controllers
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AdminJenisSampahController.php
│   │   │   ├── AdminKatalogController.php
│   │   │   ├── AdminUserApprovalController.php
│   │   │   ├── AdminUserController.php
│   │   │   ├── AdminPenarikanPoinController.php
│   │   │   └── PointRateController.php
│   │   ├── PelakuUsaha/      # Business owner controllers
│   │   │   ├── DashboardController.php
│   │   │   └── ProductController.php
│   │   ├── Petugas/          # Field worker controllers
│   │   │   └── PetugasTransaksiController.php
│   │   ├── TransaksiController.php        # Nasabah pickup requests
│   │   ├── PoinController.php             # Point balance viewer
│   │   ├── PenarikanPoinController.php    # Withdrawal requests
│   │   ├── KatalogController.php          # Product catalog
│   │   ├── ProductPurchaseController.php  # Point redemption
│   │   └── BeritaController.php           # Dashboard news feed
│   ├── Middleware/
│   │   ├── EnsureUserHasRole.php         # RBAC middleware
│   │   ├── EnsureUserIsAuthenticated.php
│   │   └── RedirectIfAuthenticated.php
│   └── Responses/
│       ├── LoginResponse.php             # Custom post-login redirects
│       └── RegisterResponse.php          # Post-registration workflow
├── Mail/                      # Email notification templates
│   ├── AdminRegistrationNotification.php
│   ├── UserRegistrationApproved.php
│   ├── UserRegistrationPending.php
│   └── UserRegistrationRejected.php
├── Models/                    # Eloquent ORM models
│   ├── User.php              # Core user model with RBAC methods
│   ├── Role.php
│   ├── JenisSampah.php
│   ├── Transaksi.php
│   ├── Poin.php
│   ├── PenarikanPoin.php
│   ├── Product.php
│   ├── ProductOrder.php
│   ├── PelakuUsahaProfile.php
│   └── Setting.php           # Key-value configuration store
├── Providers/                 # Service container bootstraps
│   ├── AppServiceProvider.php
│   ├── FortifyServiceProvider.php
│   └── JetstreamServiceProvider.php
└── View/
    └── Components/            # Blade layout components
        ├── AppLayout.php
        └── GuestLayout.php

database/
├── migrations/                # Sequenced schema evolution
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2026_04_23_141828_create_jenis_sampah_table.php
│   ├── 2026_04_23_141829_create_transaksi_table.php
│   ├── 2026_04_23_141831_create_poin_table.php
│   ├── 2026_04_26_000001_create_roles_and_user_roles_tables.php
│   ├── 2026_05_10_055236_add_status_to_users_table.php
│   ├── 2026_05_12_000001_create_settings_table.php
│   ├── 2026_05_12_000002_create_products_table.php
│   └── 2026_05_12_000003_create_product_orders_table.php
└── seeders/                   # Data population scripts
    ├── DatabaseSeeder.php     # Master seeder orchestrator
    ├── UserSeeder.php         # Create Nasabah accounts
    ├── AdminSeeder.php        # Create admin accounts
    ├── JenisSampahSeeder.php  # Populate waste types
    ├── PelakuUsahaSeeder.php  # Create business owners
    ├── TransaksiSeeder.php    # Sample pickup requests
    ├── PoinSeeder.php         # Sample point allocations
    └── PenarikanPoinSeeder.php # Sample withdrawals

docker/                        # Container configuration files
├── entrypoint.sh             # Container initialization script

nginx/
└── default.conf              # Nginx config (for production deployment)

resources/
├── views/                    # Blade templates
│   ├── admin/               # Admin-specific views
│   ├── petugas/             # Petugas-specific views
│   ├── pelaku_usaha/        # Business owner views
│   ├── common/              # Shared views (dashboard, katalog)
│   └── layouts/             # Layout templates
├── js/                      # Vite JavaScript assets
│   └── app.js
└── css/                     # Vite CSS assets
    └── app.css

routes/
├── web.php                  # Main application routes
├── api.php                  # API routes (Sanctum-protected)
└── console.php              # Artisan command definitions

public/
├── index.php                # Application entry point
└── storage/                 # Symlinked storage for uploaded files

storage/
├── app/                     # Application-generated files
├── framework/               # Framework cache & sessions
└── logs/                    # Application logs

tests/                       # PHPUnit test suites
├── Feature/
└── Unit/
```

**Architectural Pattern:**
- **MVC with Service Layer:** Controllers remain thin, complex logic in Model methods
- **Repository Pattern:** Not strictly implemented, Eloquent used directly
- **Action Pattern:** Used exclusively for Fortify auth operations
- **Transaction Script:** DB transactions wrap critical state changes (points, purchases)
- **Fat Models:** Business logic concentrated in Eloquent models with custom methods

---

## 5. Installation & Setup (Docker)

This project uses Docker for local development with isolated services.

### Requirements

- Docker Desktop (macOS/Windows/Linux)
- Docker Compose v2 (included in Docker Desktop)
- Git

> **macOS users:** Enable VirtioFS in Docker Desktop (Settings → General → VirtioFS) for better bind mount performance.

### Windows (Laragon) Quick Notes

- **Stop Laragon services first** (Apache/Nginx/MySQL) to avoid port conflicts (8000, 3306, 8080)
- Use PowerShell equivalent commands:
  ```powershell
  Copy-Item .env.example .env
  ```
- `WWWUSER` / `WWWGROUP` are for Unix/macOS file ownership. On Windows + Docker Desktop, you can keep defaults.
- If using WSL2, run UID/GID configuration from inside WSL:
  ```bash
  echo "WWWUSER=$(id -u)" >> .env
  echo "WWWGROUP=$(id -g)" >> .env
  ```

### Quick Start

**1. Clone repository**
```bash
git clone <your-repo-url>
cd ta-private
```

**2. Create environment file**
```bash
cp .env.example .env
```

**3. Configure UID/GID (macOS/Linux only - important for file permissions)**
```bash
echo "WWWUSER=$(id -u)" >> .env
echo "WWWGROUP=$(id -g)" >> .env
```

**4. Update `.env` for Docker environment**
```dotenv
APP_NAME="Bank Sampah"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database configuration (matches docker-compose.yml)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=bank_sampah
DB_USERNAME=laravel
DB_PASSWORD=secret

# Docker MySQL root password
DB_ROOT_PASSWORD=root

# Session & Cache (use database driver for Docker)
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Mail (use log driver for development)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@banksampah.test"
MAIL_FROM_NAME="${APP_NAME}"
```

**5. Build and start services**
```bash
docker compose up -d --build
```

This starts:
- `app` - PHP 8.2 application server (port 8000)
- `db` - MySQL 8.0 database (port 3306)
- `phpmyadmin` - Database admin interface (port 8080)
- `node` - Vite development server (port 5173)

**6. Install PHP dependencies**
```bash
docker compose exec app composer install
```

**7. Generate application key**
```bash
docker compose exec app php artisan key:generate
```

**8. Run migrations and seed database**
```bash
# Fresh migration + seed (recommended for first setup)
docker compose exec app php artisan migrate:fresh --seed
```

This creates:
- Admin accounts: `admin+1@local.invalid`, `admin+2@local.invalid`, `admin+3@local.invalid` (password: `password`)
- Sample Nasabah users
- Sample Petugas accounts
- Sample Pelaku Usaha accounts
- Waste type master data
- Sample transactions and point allocations

**9. Wait for Vite to be ready**
```bash
docker compose logs -f node
```
Watch for: `VITE ... ready in XXXms` before accessing the application.

Press `Ctrl+C` to stop following logs.

### Access URLs

| Service | URL | Credentials |
|---------|-----|-------------|
| **Application** | http://localhost:8000 | See seeded accounts |
| **Vite Dev Server** | http://localhost:5173 | N/A |
| **phpMyAdmin** | http://localhost:8080 | Server: `db`<br>Username: `laravel`<br>Password: `secret` |

### Default Login Accounts

After seeding, you can login with these accounts:

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin+1@local.invalid` | `password` |
| Admin | `admin+2@local.invalid` | `password` |
| Admin | `admin+3@local.invalid` | `password` |

Additional Nasabah, Petugas, and Pelaku Usaha accounts are created by seeders with random data.

### Useful Commands

```bash
# View running containers
docker compose ps

# Start all services
docker compose up -d

# Stop all services
docker compose down

# Stop and remove volumes (WARNING: deletes MySQL data)
docker compose down -v

# View logs for all services
docker compose logs -f

# View logs for specific service
docker compose logs -f app
docker compose logs -f node

# Open app container shell
docker compose exec app bash

# Run Artisan commands
docker compose exec app php artisan <command>

# Examples:
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan route:list
docker compose exec app php artisan tinker

# Restart PHP server (if app hangs)
docker compose restart app

# Restart Vite (if hot reload stops working)
docker compose restart node

# Rebuild containers (after Dockerfile changes)
docker compose up -d --build

# Run Composer commands
docker compose exec app composer <command>
```

### Common Issues & Solutions

#### **1. Port conflicts**
**Error:** `Bind for 0.0.0.0:8000 failed: port is already allocated`

**Solution:**
- Stop conflicting services (Laragon, XAMPP, other Docker projects)
- Or change ports in `docker-compose.yml`:
  ```yaml
  ports:
    - "8001:8000"  # Change host port to 8001
  ```

#### **2. Permission denied on storage/bootstrap**
**Error:** `failed to open stream: Permission denied`

**Solution:**
```bash
# Ensure WWWUSER/WWWGROUP are set in .env
docker compose down
docker compose build --no-cache app
docker compose up -d
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

#### **3. Vite not detecting file changes (macOS)**
**Issue:** Editing files doesn't trigger hot reload

**Solution:**
- Polling is already enabled in `docker-compose.yml` via `CHOKIDAR_USEPOLLING=true`
- Ensure `node` service is running: `docker compose ps node`
- Check Vite logs: `docker compose logs -f node`

#### **4. MySQL connection refused**
**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**
```bash
# Wait for MySQL to be healthy
docker compose ps db

# If unhealthy, check MySQL logs
docker compose logs db

# Common fix: remove corrupted volume
docker compose down -v
docker compose up -d
```

#### **5. Composer out of memory**
**Error:** `Fatal error: Allowed memory size exhausted`

**Solution:**
```bash
docker compose exec app php -d memory_limit=-1 /usr/bin/composer install
```

#### **6. Frontend assets not loading**
**Issue:** Styles/scripts return 404

**Solution:**
- Ensure Vite dev server is running: `docker compose logs node`
- Check `vite.config.js` server configuration
- Clear browser cache or try incognito mode
- Restart both app and node:
  ```bash
  docker compose restart app node
  ```

### Development Workflow

**Starting work:**
```bash
docker compose up -d
docker compose logs -f node  # Wait for "ready"
# Open http://localhost:8000
```

**Making code changes:**
- PHP changes: Automatic (no restart needed)
- Blade template changes: Automatic via Vite HMR
- CSS/JS changes: Automatic via Vite HMR
- Config/route changes: Restart app: `docker compose restart app`

**Ending work:**
```bash
docker compose down  # Stops containers, keeps data
```

### Notes

- **Data Persistence:** MySQL data stored in named volume `db_data` (survives container restarts)
- **Database Auto-creation:** Database `bank_sampah` created automatically on first MySQL initialization
- **Node Modules:** Stored in named volume for performance (not on host filesystem)
- **Composer Cache:** Cached in named volume to speed up dependency installation
- **Built-in PHP Server:** Development uses `php artisan serve`, not Nginx (Nginx config available for production)
- **Hot Module Replacement:** Vite provides instant feedback for frontend changes
- **Seeders:** Always run `migrate:fresh --seed` for clean state during development

### Production Deployment Notes

For production, this Docker setup is **not recommended**. Instead:

1. Use proper web server (Nginx + PHP-FPM)
2. See [Section 6: Installation Without Docker](#6-instalasi-tanpa-docker-windowslaragon-atau-macoslinux-native) for server setup
3. Use separate database server (managed MySQL/MariaDB)
4. Build frontend assets: `npm run build`
5. Enable Laravel optimization:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

---

## 6. Installation Without Docker (Native Setup)

If you prefer to run the project without Docker, follow the guide below for your operating system.

### Universal Requirements

**All platforms need:**
- PHP 8.2 or higher
- Composer (PHP dependency manager)
- MySQL 8.0+ or MariaDB 10.4+
- Node.js 20+ and npm
- Git

**Required PHP Extensions:**
- `pdo` and `pdo_mysql`
- `mbstring`
- `exif`
- `pcntl`
- `bcmath`
- `gd`
- `zip`
- `intl`
- `opcache`
- `xml`

Verify with: `php -m | grep -E "pdo|mbstring|gd|zip|intl"`

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

### Production Deployment Checklist

Before deploying to production:

**1. Environment Configuration**
```dotenv
APP_ENV=production
APP_DEBUG=false
APP_KEY=<use-php-artisan-key-generate>
APP_URL=https://your-domain.com

# Use production database credentials
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=bank_sampah
DB_USERNAME=production_user
DB_PASSWORD=strong_random_password

# Use Redis for better performance
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Configure real SMTP server
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Bank Sampah"
```

**2. Build Frontend Assets**
```bash
npm install
npm run build  # Creates optimized production bundle
```

**3. Laravel Optimization**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

**4. Web Server Configuration**

**Nginx Configuration (Recommended for Production):**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com;
    root /var/www/ta-private/public;
    
    index index.php;
    
    # SSL Configuration
    ssl_certificate /etc/ssl/certs/yourdomain.crt;
    ssl_certificate_key /etc/ssl/private/yourdomain.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    
    # Logging
    access_log /var/log/nginx/banksampah-access.log;
    error_log /var/log/nginx/banksampah-error.log;
    
    # Max upload size
    client_max_body_size 20M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

**5. File Permissions**
```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/ta-private

# Set directory permissions
sudo find /var/www/ta-private -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/ta-private -type f -exec chmod 644 {} \;

# Writable directories
sudo chmod -R 775 /var/www/ta-private/storage
sudo chmod -R 775 /var/www/ta-private/bootstrap/cache
```

**6. Database Backups**
```bash
# Automated daily backup script
#!/bin/bash
BACKUP_DIR="/var/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u root -p bank_sampah | gzip > $BACKUP_DIR/bank_sampah_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -name "bank_sampah_*.sql.gz" -mtime +30 -delete
```

Add to crontab:
```bash
0 2 * * * /path/to/backup-script.sh
```

**7. Queue Workers (Optional)**

If using queues (e.g., for email notifications):
```bash
# Install Supervisor
sudo apt install supervisor

# Create worker config: /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ta-private/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/ta-private/storage/logs/worker.log
stopwaitsecs=3600

# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

**8. Monitoring & Logging**

- Monitor `storage/logs/laravel.log` regularly
- Set up error tracking (Sentry, Bugsnag, Flare)
- Monitor disk space (especially `storage/` directory)
- Set up uptime monitoring (UptimeRobot, Pingdom)

**9. Security Checklist**
- [ ] Enable HTTPS with valid SSL certificate
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong `APP_KEY` (never commit to Git)
- [ ] Database user has minimal required permissions
- [ ] Storage directory not web-accessible
- [ ] Firewall configured (only 80, 443, 22 open)
- [ ] Regular security updates: `composer update`, `npm audit fix`
- [ ] CSRF protection enabled (Laravel default)
- [ ] XSS protection via Blade escaping
- [ ] SQL injection protection via Eloquent
- [ ] Rate limiting configured for API routes

**10. Performance Optimization**
```bash
# Enable PHP OPcache (php.ini)
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0  # Production only

# Laravel optimization
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database optimization
php artisan db:show  # Verify indexes
```

---

### Rollback Procedure

If deployment fails:

```bash
# 1. Restore database backup
gunzip < /var/backups/mysql/bank_sampah_YYYYMMDD_HHMMSS.sql.gz | mysql -u root -p bank_sampah

# 2. Revert code to previous version
git checkout previous-stable-tag

# 3. Clear caches
php artisan optimize:clear

# 4. Rebuild caches
php artisan optimize

# 5. Restart services
sudo systemctl restart php8.2-fpm nginx
```

---

### Maintenance Mode

Before major updates:

```bash
# Enable maintenance mode
php artisan down --refresh=15 --message="Scheduled maintenance in progress"

# Perform updates
git pull
composer install --no-dev
npm install && npm run build
php artisan migrate --force

# Clear and rebuild caches
php artisan optimize:clear
php artisan optimize

# Disable maintenance mode
php artisan up
```


---

## 7. System Features & Workflows

### 7.1 User Registration & Approval System

**Flow:**
1. User registers with role selection (Nasabah, Petugas, or Pelaku Usaha)
2. Account created with `status = 'pending'` (except Nasabah - auto-approved)
3. Admin receives email notification (`AdminRegistrationNotification`)
4. Admin reviews registration in Admin Panel → User Approvals
5. Admin approves or rejects:
   - **Approved:** User status → `approved`, activation email sent
   - **Rejected:** User status → `rejected`, rejection email sent
6. Approved users can login and access role-specific features

**Email Notifications:**
- `UserRegistrationPending` - Sent to user after registration
- `AdminRegistrationNotification` - Sent to admins for review
- `UserRegistrationApproved` - Sent when admin approves
- `UserRegistrationRejected` - Sent when admin rejects

**Admin Actions:**
```
Route: /admin/approvals
- View pending user registrations
- Approve: POST /admin/approvals/{user}
- Reject: POST /admin/approvals/{user}/reject
```

---

### 7.2 Waste Pickup Transaction Workflow

**Nasabah Perspective:**
1. Submit pickup request via `/penjemputan/create`
   - Select waste type (`jenis_sampah`)
   - Enter quantity in kg
   - Provide pickup address
2. View history at `/penjemputan/history`
   - Track status updates in real-time
   - Edit pending requests
   - Delete cancelled requests

**Admin Perspective:**
1. Review pending pickup requests in admin dashboard
2. Assign Petugas to handle pickup
3. Monitor completion status

**Petugas Perspective:**
1. View assigned pickups at `/petugas`
2. Update status through workflow:
   ```
   Menunggu Petugas → Menuju Lokasi → Sedang Diangkut → Selesai
   ```
3. When status changed to `Selesai`:
   - **Automatic Point Calculation:** `points = ceil(quantity_kg × price_per_kg)`
   - **Atomic DB Transaction:**
     - Add points to `users.total_poin`
     - Create immutable record in `poin` table
     - Link to `transaksi_id` for audit trail

**Business Rules:**
- Only assigned Petugas can update transaction
- Completed transactions (`Selesai`) cannot be modified
- Point calculation uses `jenis_sampah.harga_sampah` as rate
- All point operations wrapped in database transactions

---

### 7.3 Point Management System

**Point Earning:**
- Automatic allocation when Petugas completes pickup
- Formula: `ceil(waste_quantity_kg × waste_type_price)`
- Stored in immutable `poin` ledger with `transaksi_id` reference

**Point Balance:**
- View at `/poin` (Nasabah only)
- Shows total points and transaction history
- Displays point earning events with dates

**Point Conversion Rate:**
- Configurable by Admin at `/admin/settings/point-rate`
- Default: 1 point = Rp 1,000
- Used for product pricing in marketplace
- Formula: `product_points = ceil(product_price_rupiah / rate)`

**Point Redemption Methods:**
1. **Cash Withdrawal** - Request payout via `/penarikan-poin`
2. **Product Purchase** - Spend points in marketplace `/katalog`

---

### 7.4 Cash Withdrawal System

**Nasabah Flow:**
1. Navigate to `/penarikan-poin`
2. Enter withdrawal amount (in points)
3. Submit request → Status: `pending`
4. Wait for admin approval

**Admin Flow:**
1. Review requests at `/admin/penarikan-poin`
2. Verify user account and point balance
3. Process withdrawal:
   - **Approve:** Deduct points, mark as `approved`, transfer cash offline
   - **Reject:** Keep points, mark as `rejected`, notify user

**Business Rules:**
- Cannot withdraw more than current `total_poin`
- Pending requests lock the requested points
- Admin approval required for all withdrawals
- Cash transfer handled outside system (manual bank transfer)

---

### 7.5 Marketplace & Product Catalog

**Pelaku Usaha Features:**
- Access dashboard at `/pelaku-usaha/dashboard`
- Manage products via `/pelaku-usaha/products`:
  - Create new products
  - Set price in Rupiah
  - Upload product images
  - Manage inventory stock
  - Toggle product availability (`is_active`)
- View sales history and revenue

**Product Management:**
```php
Product Model:
- name (string)
- description (text)
- price_rupiah (integer)
- stock (integer)
- image_path (string, nullable)
- is_active (boolean)
- pelaku_usaha_id (foreign key to users)
```

**Nasabah Shopping:**
1. Browse catalog at `/katalog` (public access)
2. View product details and point cost
3. Purchase with points (authenticated only)
   - System calculates required points
   - Validates sufficient balance and stock
   - Atomic transaction:
     - Deduct points from user
     - Decrement product stock
     - Create order record
4. View purchase history in profile

**Purchase Transaction:**
```php
ProductOrder Model:
- user_id
- product_id
- quantity
- unit_price_rupiah (snapshot at purchase time)
- total_price_rupiah
- points_spent (calculated conversion)
- status (default: 'paid')
- timestamps
```

**Business Rules:**
- Products priced in Rupiah, converted to points at checkout
- Stock validation with pessimistic locking (prevents overselling)
- Point balance validated before purchase
- Order history immutable (no cancellations)
- Pelaku Usaha can only manage their own products

---

### 7.6 Waste Type Management (Admin)

**CRUD Operations at `/admin/jenis-sampah`:**
- Create new waste types
- Update pricing per kg
- Delete unused waste types
- Categories influence point calculation

**JenisSampah Schema:**
```php
- jenis_sampah_id (primary key)
- nama_sampah (string) - e.g., "Plastik", "Kertas", "Logam"
- harga_sampah (decimal) - price/points per kg
- created_at, updated_at
```

**Impact:**
- Changes to `harga_sampah` affect future transactions only
- Historical transactions maintain original rates
- Used in point calculation: `points = quantity × harga_sampah`

---

### 7.7 User Management (Admin)

**Admin Panel (`/admin/users`):**
- View all users across roles
- Edit user information:
  - Name, email, phone
  - Address
  - Role assignments
- Delete user accounts (cascade deletes related data)
- Search and filter by role

**Role Assignment:**
- Users can have multiple roles via `user_roles` pivot table
- Available roles:
  - `user` / `nasabah` (standard user - aliases)
  - `admin` (system administrator)
  - `petugas` (field worker)
  - `pelaku_usaha` (business owner)

---

### 7.8 Dashboard & Analytics

**Nasabah Dashboard (`/dashboard`):**
- News feed integration (BeritaController)
- Quick stats: total points, pending pickups
- Recent transactions
- Quick actions: request pickup, view catalog

**Admin Dashboard (`/admin/dashboard`):**
- System-wide statistics:
  - Total users by role
  - Pending approvals count
  - Recent transactions
  - Total waste collected
  - Point distribution analytics
- Pending action items
- Recent activity feed

**Petugas Dashboard (`/petugas`):**
- Assigned pickup tasks
- Task queue sorted by status
- Quick status update actions
- Daily completion statistics

**Pelaku Usaha Dashboard (`/pelaku-usaha/dashboard`):**
- Sales revenue summary
- Product performance metrics
- Low stock alerts
- Recent orders
- Customer insights

---

## 8. API Documentation

### Current API Endpoints

**Authentication Required (Sanctum):**

All API routes require `Authorization: Bearer {token}` header.

**Available Endpoints:**

```http
GET /api/user
```
Returns authenticated user profile including roles and total points.

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "total_poin": 1500,
  "alamat": "Jl. Example No. 123",
  "nomor_telepon": "08123456789",
  "status": "approved",
  "roles": [
    {"id": 1, "name": "user"}
  ]
}
```

### Future API Expansion

The Sanctum foundation allows easy expansion for mobile apps:
- Token-based authentication
- RESTful resource endpoints
- Rate limiting support
- API versioning ready

**Recommended Future Endpoints:**
```
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/transaksi
POST   /api/transaksi
GET    /api/poin
GET    /api/katalog
POST   /api/katalog/{id}/purchase
```

---

## 9. Testing & Quality Assurance

### Manual Testing Checklist

**User Registration & Authentication:**
- [ ] Register as Nasabah (auto-approved)
- [ ] Register as Petugas (requires approval)
- [ ] Register as Pelaku Usaha (requires approval)
- [ ] Admin receives notification email
- [ ] Admin can approve/reject registrations
- [ ] Approved users can login
- [ ] Rejected users cannot login
- [ ] Password reset workflow

**Waste Pickup Flow:**
- [ ] Nasabah can submit pickup request
- [ ] Petugas sees assigned tasks
- [ ] Status updates work correctly
- [ ] Points calculated and awarded on completion
- [ ] Point ledger created with transaksi_id
- [ ] User total_poin updated correctly

**Point System:**
- [ ] View point balance
- [ ] View point history with sources
- [ ] Point calculation matches formula
- [ ] Cannot spend more points than balance
- [ ] Withdrawal request creation
- [ ] Admin approval deducts points

**Marketplace:**
- [ ] Pelaku Usaha can create products
- [ ] Products visible in catalog
- [ ] Point conversion calculated correctly
- [ ] Purchase deducts points and stock
- [ ] Out of stock products cannot be purchased
- [ ] Order history recorded

**Admin Functions:**
- [ ] Manage waste types (CRUD)
- [ ] Approve/reject user registrations
- [ ] Manage user accounts
- [ ] Configure point conversion rate
- [ ] Review and approve withdrawals
- [ ] View system analytics

### Automated Testing

Run PHPUnit tests:
```bash
# Docker
docker compose exec app php artisan test

# Native
php artisan test
```

**Test Coverage Areas:**
- Model relationships
- Authentication flows
- Point calculation logic
- Transaction atomicity
- Role-based access control

### Load Testing

Recommended tools:
- Apache JMeter
- Locust
- K6

Focus areas:
- Concurrent pickup requests
- Simultaneous product purchases
- Point balance updates under load

---

## 10. Troubleshooting Guide

### Common Errors

#### "SQLSTATE[42S02]: Base table or view not found"
**Cause:** Migrations not run or database not seeded

**Solution:**
```bash
docker compose exec app php artisan migrate:fresh --seed
```

#### "Class 'App\Models\X' not found"
**Cause:** Composer autoload cache out of date

**Solution:**
```bash
docker compose exec app composer dump-autoload
```

#### "419 | Page Expired" on form submission
**Cause:** CSRF token mismatch or session expired

**Solution:**
- Check `@csrf` directive in forms
- Clear browser cache
- Verify `SESSION_DRIVER=database` in .env
- Check session table exists: `php artisan session:table && php artisan migrate`

#### Points not being awarded after pickup completion
**Cause:** Transaction failure or validation error

**Debug:**
```bash
# Check Laravel logs
docker compose exec app tail -f storage/logs/laravel.log

# Verify JenisSampah has harga_sampah > 0
docker compose exec app php artisan tinker
>>> App\Models\JenisSampah::all();
```

#### "403 Forbidden" on admin routes
**Cause:** User lacks required role

**Solution:**
```bash
# Check user roles in database
docker compose exec app php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->roles;

# Assign admin role
>>> $adminRole = App\Models\Role::firstOrCreate(['name' => 'admin']);
>>> $user->roles()->attach($adminRole->id);
```

#### Product images not displaying
**Cause:** Storage link not created

**Solution:**
```bash
docker compose exec app php artisan storage:link
```

#### Email notifications not sending
**Cause:** Mail configuration incorrect

**Debug:**
- Check `.env` mail settings
- For development, use `MAIL_MAILER=log`
- Check logs: `storage/logs/laravel.log`
- For production SMTP, test credentials:
  ```bash
  docker compose exec app php artisan tinker
  >>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
  ```

---

## 11. Contributing Guidelines

### Code Style

- Follow PSR-12 coding standards
- Use Laravel best practices
- Run Laravel Pint before committing:
  ```bash
  docker compose exec app ./vendor/bin/pint
  ```

### Git Workflow

1. Create feature branch: `git checkout -b feature/your-feature`
2. Make changes and commit with descriptive messages
3. Run tests: `php artisan test`
4. Push and create pull request
5. Request code review

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Build process or auxiliary tool changes

**Example:**
```
feat(marketplace): add product search functionality

Implemented product search with filters for category and price range.
Optimized database queries with indexes.

Closes #123
```

### Pull Request Checklist

- [ ] Code follows PSR-12 standards
- [ ] All tests pass
- [ ] New features have tests
- [ ] Documentation updated (README, comments)
- [ ] Database migrations are reversible
- [ ] No sensitive data in commits (.env, credentials)
- [ ] Branch is up to date with main

---

## 12. License & Credits

### License

This project is proprietary software developed for academic purposes (Tugas Akhir).

### Technology Stack Credits

- **Laravel Framework** - Taylor Otwell and contributors
- **Laravel Jetstream** - Authentication scaffolding
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Next-generation frontend tooling
- **MySQL** - Database management system

### Development Team

[Add your team information here]

---

## 13. FAQ

**Q: What's the difference between Admin and Pelaku Usaha roles?**
A: Admin manages the waste management system (user approvals, waste types, point rates). Pelaku Usaha manages the marketplace (products, inventory, pricing).

**Q: Can a user have multiple roles?**
A: Yes, the RBAC system supports multiple role assignments via the `user_roles` pivot table.

**Q: What happens to points when a transaction is deleted?**
A: Transactions cannot be deleted once points are awarded. The immutable `poin` ledger prevents retroactive changes.

**Q: How is the point conversion rate applied?**
A: For marketplace purchases: `required_points = ceil(product_price_rupiah / rate)`. For waste collection: points are awarded directly based on waste type pricing.

**Q: Can Nasabah see other users' transactions?**
A: No, Nasabah can only view their own pickup history and point balance. Only Admin and Petugas have cross-user visibility.

**Q: What happens if stock becomes negative?**
A: Pessimistic database locking prevents overselling. The purchase transaction validates stock availability before processing.

**Q: How do I reset my development database?**
A: Run `docker compose exec app php artisan migrate:fresh --seed` to drop all tables, re-run migrations, and seed fresh data.

**Q: Can I use SQLite instead of MySQL?**
A: For development only. Change `DB_CONNECTION=sqlite` in `.env` and create `database/database.sqlite`. Production should use MySQL.

**Q: How do I add a new role?**
A: Create migration to insert role in `roles` table, update seeders, add role check in middleware, and create role-specific routes.

**Q: Where are uploaded product images stored?**
A: In `storage/app/public/products/`. Ensure storage link exists: `php artisan storage:link`

---

**Document Version:** 2.0  
**Last Updated:** July 1, 2026  
**Status:** Production Ready
