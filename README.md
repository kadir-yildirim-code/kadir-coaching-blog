# Kadir Coaching – Dynamic Blog & Content Management System

A PHP and MySQL web application built as a portfolio project. It includes a public blog, category-based content browsing, post detail pages, a view counter, and an authenticated admin area for managing posts.

> **Source code location:** The application files are in the [`kadir-coaching-blog-source/`](./kadir-coaching-blog-source/) directory. This folder is the web application root.

## Features

- Public blog homepage with category filtering
- Post detail pages and view counts
- Admin login with `password_hash()` / `password_verify()`
- Session-based admin authentication, session ID regeneration, and explicit admin-role checks
- Admin dashboard with category totals
- Admin post list with title search, category filtering, and pagination (10 posts per page)
- Create, read, update and delete (CRUD) operations for posts
- Prepared MySQLi statements for user authentication and post changes
- CSRF tokens on admin forms
- Responsive interface built with Bootstrap 5

## Tech stack

- PHP 8.1+
- MySQL 8+ or MariaDB
- MySQLi and SQL
- HTML5, CSS3, Bootstrap 5
- JavaScript (Bootstrap bundle)

## Local setup (XAMPP / Laragon)

1. Clone or download this repository.
2. Configure your local web server so that `kadir-coaching-blog-source/` is the document root for the site, or copy the **contents** of that folder into your local web root (for example, `htdocs/kadir-coaching-blog/` in XAMPP or `www/kadir-coaching-blog/` in Laragon).
3. Inside `kadir-coaching-blog-source/`, copy `config.example.php` to `config.local.php`.
4. Update the database username and password in `config.local.php`. The local configuration file is excluded by the project's `.gitignore`.
5. Start Apache and MySQL.
6. Open phpMyAdmin and import `kadir-coaching-blog-source/database/schema.sql`. The script creates the `kadir_coaching` database, the `posts` and `users` tables, and sample post content. It does **not** create an administrator account.
7. For a fresh database, run `php scripts/create_admin.php` from inside `kadir-coaching-blog-source/` and follow the prompts. The script creates an `admin`-role account and stores the password as a hash.
8. **When upgrading an older database**, first apply `kadir-coaching-blog-source/database/migrations/001_add_user_role.sql` once, before using the admin panel or running the admin creation script. Existing accounts become `user` by default; edit and run the example `UPDATE` statement in that migration for the one account that should remain an administrator.
9. Open the site in your browser, for example `http://localhost/kadir-coaching-blog/`. The admin login is at `/admin/admingiris.php`.

## Configuration and security

- The public repository does not contain the original local database credentials or administrator account.
- Create a new local admin account with the CLI script; do not reuse a password from an old development setup.
- Admin pages require both an authenticated session and the explicit `admin` role.
- The post editor accepts HTML so formatted articles can be displayed. Only trusted administrators should have access to the admin account, and only trusted HTML should be published.
- Optional contact links are disabled by default. Add your own values only to local configuration; do not commit private contact details.
- Bootstrap is loaded from the jsDelivr CDN.

## Project context

This is a learning and portfolio project demonstrating server-side PHP, relational database operations, session handling, admin CRUD workflows, and responsive page construction. It is not presented as a production-ready CMS.
