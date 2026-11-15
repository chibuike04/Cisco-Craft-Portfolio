# Cisco Craft database setup

This project uses MySQL, PDO, Composer, phpdotenv, and Phinx.

## 1. Requirements

- PHP 8.1 or newer
- MySQL 8.0+ (or a compatible MariaDB release)
- PHP extensions `pdo` and `pdo_mysql`
- Composer

## 2. Install PHP dependencies

Run these commands from the project root:

```bash
composer install
```

## 3. Create the database

Start MySQL, then create an empty database using phpMyAdmin or the MySQL client:

```sql
CREATE DATABASE cisco_craft_portfolio
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

## 4. Configure environment variables

Copy `.env.example` to `.env` and set the correct local MySQL username and password. Do not commit `.env` to source control.

## 5. Run migrations

```bash
vendor/bin/phinx migrate
```

On Windows PowerShell, if the executable is not resolved:

```powershell
.\vendor\bin\phinx.bat migrate
```

## 6. Insert the initial roles

```bash
vendor/bin/phinx seed:run -s RolesSeeder
```

The seeder creates the `administrator` and `editor` roles. It does not create an admin login; we will create that securely in the authentication step using PHP's `password_hash()`.

## 7. Check migration status

```bash
vendor/bin/phinx status
```

## 8. Test the PDO connection

After dependencies are installed and `.env` is configured, run:

```bash
php test_db.php
```

Do not expose database credentials in screenshots or commit them to Git.
