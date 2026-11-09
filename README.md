# Kadir Coaching – Dynamic Blog & Content Management System

A PHP and MySQL web application built as a portfolio project. It includes a public blog, category-based content browsing, post detail pages, a view counter, and an authenticated admin area for managing posts.

> **Source code location:** The application files are in the [`kadir-coaching-blog-source/`](./kadir-coaching-blog-source/) directory. This folder is the web application root.

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
7. From inside `kadir-coaching-blog-source/`, run `php scripts/create_admin.php` in a terminal and follow the prompts. The password is stored as a hash.
8. Open the site in your browser, for example `http://localhost/kadir-coaching-blog/`. The admin login is at `/admin/admingiris.php`.

## Configuration and security

- The public repository does not contain the original local database credentials or administrator account.
- Create a new local admin account with the CLI script; do not reuse a password from an old development setup.
- The post editor accepts HTML so formatted articles can be displayed. Only trusted administrators should have access to the admin account, and only trusted HTML should be published.
- Optional contact links are disabled by default. Add your own values only to local configuration; do not commit private contact details.
- Bootstrap is loaded from the jsDelivr CDN.

## Project context

This is a learning and portfolio project demonstrating server-side PHP, relational database operations, session handling, admin CRUD workflows, and responsive page construction. It is not presented as a production-ready CMS.
