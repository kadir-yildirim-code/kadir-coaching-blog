# Kadir Coaching – Dynamic Blog & Content Management System

A PHP and MySQL web application built as a portfolio project. It includes a public blog, category-based content browsing, post detail pages, a view counter, and an authenticated admin area for managing posts.

## Features

- Public blog homepage with category filtering
- Post detail pages and view counts
- Admin login with `password_hash()` / `password_verify()`
- Session-based admin authentication and session ID regeneration
- Admin dashboard with category totals
- Create, read, update and delete (CRUD) operations for posts
- Prepared MySQLi statements for user authentication and post changes
- CSRF tokens on admin forms
- Responsive interface built with Bootstrap 5
- Configurable contact links; personal contact details are not committed

## Tech stack

- PHP 8.1+
- MySQL 8+ or MariaDB
- MySQLi and SQL
- HTML5, CSS3, Bootstrap 5
- JavaScript (Bootstrap bundle)

## Local setup (XAMPP / Laragon)

1. Place the project folder in your local web root (for example, `htdocs` in XAMPP or `www` in Laragon).
2. Copy `config.example.php` to `config.local.php`.
3. Update the database username and password in `config.local.php`. This local file is ignored by Git.
4. Start Apache and MySQL.
5. Open phpMyAdmin and import `database/schema.sql`. The script creates the `kadir_coaching` database, the `posts` and `users` tables, and sample post content. It does **not** create an administrator account.
6. From the project directory, run `php scripts/create_admin.php` in a terminal and follow the prompts. The password is stored as a hash.
7. Open the project in your browser, for example `http://localhost/kadir-coaching-blog/`. The admin login is at `/admin/admingiris.php`.

## Configuration

`config.local.php` is intentionally excluded from version control. You can optionally set `whatsapp_number` and `instagram_url` in that file to display contact links on the site. Leave them blank to keep those links hidden. Never place real passwords, tokens, or private contact information in committed source files.

## Security notes

- The public repository does not contain the original local database credentials or administrator account.
- Create a new local admin account with the CLI script; do not reuse a password from a previous development setup.
- The post editor accepts trusted HTML so that formatted articles can be displayed. Only trusted administrators should have access to the admin account, and only trusted HTML should be published.
- Bootstrap is loaded from the jsDelivr CDN.

## Project context

This is a learning and portfolio project demonstrating server-side PHP, relational database operations, session handling, admin CRUD workflows, and responsive page construction. It is not presented as a production-ready CMS.
