# Kadir Coaching

Dynamic blog and content management application built with PHP and MySQL.

## Features

- Category-based blog listing and post detail pages
- Post view counter
- Admin login with hashed password verification
- Session-based authentication with an explicit admin-role check
- Admin dashboard
- Admin post list with title search, category filtering, and pagination (10 posts per page)
- Create, read, update and delete blog posts
- Prepared MySQLi statements for dynamic database operations
- CSRF protection on admin forms
- Responsive Bootstrap 5 interface

## Requirements

- PHP 8.1 or later with the MySQLi extension
- MySQL 8+ or MariaDB
- Apache (XAMPP or Laragon can provide the local environment)

## Setup

1. Copy `config.example.php` to `config.local.php`.
2. Set your local database connection values in `config.local.php`.
3. Start Apache and MySQL.
4. Import `database/schema.sql` using phpMyAdmin.
5. For a fresh database, run `php scripts/create_admin.php` from a terminal in this directory. It creates an `admin`-role account and stores the password hash.
6. **For an existing database**, apply `database/migrations/001_add_user_role.sql` once before using the admin panel or running the admin creation script. Existing accounts default to the `user` role; edit and run the example `UPDATE` statement in that migration for the account that should be an administrator.
7. Open the site through your local web server.

The local `config.local.php` is excluded by `.gitignore`. Never commit database passwords or other secrets. Admin pages require an authenticated session with the explicit `admin` role.

## Technologies

PHP, MySQL, MySQLi, SQL, HTML5, CSS3, Bootstrap 5, JavaScript.
