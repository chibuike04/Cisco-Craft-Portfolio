# Cisco Craft Portfolio — PHP/MySQL setup

This project now has a PHP/Composer foundation while keeping the existing portfolio pages and visual assets in place.

## Requirements

- PHP 8.1 or newer
- Composer 2
- MySQL 8 (or a compatible MySQL/MariaDB server)
- PHP extensions `pdo` and `pdo_mysql`

## 1. Install dependencies

Open a terminal in this project folder and run:

```bash
composer install
```

This installs PHP dotenv and Phinx and creates `vendor/` and `composer.lock` locally.

## 2. Configure the database

1. Copy `.env.example` to `.env`.
2. Create a MySQL database named `cisco_craft_portfolio` (or change `DB_NAME`).
3. Set `DB_USER` and `DB_PASSWORD` in `.env` to your local MySQL credentials.
4. Keep `.env` private; it is excluded by `.gitignore` and blocked by the Apache rules.

## 3. Run database migrations

After Composer dependencies are installed and MySQL is running:

```bash
vendor/bin/phinx status
vendor/bin/phinx migrate
```

On Windows PowerShell, the executable can be run as:

```powershell
.\vendor\bin\phinx.bat status
.\vendor\bin\phinx.bat migrate
```

No migration files or portfolio tables have been created yet; we will add those when we design the database.

## 4. Run the PHP development server

```bash
php -S localhost:8000
```

Then open <http://localhost:8000>.

## Project folders

- `Core/` — shared application services such as the PDO database connection.
- `db/migrations/` — Phinx database migrations.
- `db/seeds/` — optional development seed data.
- `routes/` — route definitions as the backend grows.
- `src/Controllers/` — request/controller logic.
- `src/Models/` — database models and queries.
- `ui/components/` — reusable UI components when we begin converting pages to PHP.
- `assets/` — static assets; existing animation frames remain untouched in their current folder.

The existing HTML/PHP pages have not been redesigned or rewritten as part of this setup.
