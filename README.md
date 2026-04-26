# TA Private - Laravel Project

This project runs with Docker for local development.

## Stack

- Laravel 11 (PHP 8.2)
- Nginx
- MySQL 8 (`bank_sampah`)
- phpMyAdmin
- Node 20 + Vite

## Requirements

- Docker Desktop (macOS/Windows/Linux)
- Docker Compose v2 (included in Docker Desktop)
- Git

> macOS: enable VirtioFS in Docker Desktop for better bind mount performance.

## Windows (Laragon) Quick Notes

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

## Quick Start

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

7. Generate key and run migrations

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

8. Run frontend dev server (recommended before opening app)

```bash
docker compose up -d node
docker compose logs -f node
```

Wait until Vite logs show `VITE ... ready`, then open the app.

## Access URLs

- App: http://localhost:8000
- phpMyAdmin: http://localhost:8080
	- Server: `db`
	- Username: `laravel`
	- Password: `<your_database_password>`
- Vite: http://localhost:5173

## Useful Commands

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

## Common Issues

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

## Notes

- MySQL data is persistent in named volume `db_data`.
- Database `bank_sampah` is created automatically on first MySQL initialization.
