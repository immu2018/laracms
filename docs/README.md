# Laravel CMS Documentation

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Admin UI & Usage](#admin-ui--usage)
- [Media Manager](#media-manager)
- [Roles & Permissions](#roles--permissions)
- [Extending the CMS](#extending-the-cms)
- [Best Practices](#best-practices)
- [Deployment Guide](#deployment-guide)

---

## Introduction
A modern, scalable, developer-friendly Laravel CMS with Tabler UI, role-based access, and a robust media manager.

## Features
- Modular admin UI (Tabler)
- CRUD for posts, categories, tags, menus
- Drag-and-drop menu management
- Media manager (AJAX, bulk delete, config-driven)
- Role-based access (Spatie)
- Extensible and maintainable codebase

## Installation
1. Clone the repository
2. Run `composer install` and `npm install`
3. Copy `.env.example` to `.env` and set DB credentials
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Run `php artisan storage:link`
7. Start the dev server: `php artisan serve`

## Configuration
- **Media settings:** Edit `config/media.php` for allowed file types and max size
- **Roles/permissions:** See `config/permission.php` and use Spatie commands

## Admin UI & Usage
- Access `/admin` after login
- Manage posts, categories, tags, menus, and media
- Use drag-and-drop for menu order
- Use checkboxes for bulk media delete

## Media Manager
- Upload multiple files at once
- Allowed types/size set in config
- Bulk delete with modal confirmation
- Only admins see all files; users see their own

## Roles & Permissions
- Uses Spatie Laravel Permission
- Register roles in `RolesTableSeeder.php`
- Middleware: `'role' => Spatie\Permission\Middleware\RoleMiddleware::class`

## Extending the CMS
- Add new modules/controllers in `app/Http/Controllers/Admin`
- Add new Blade views in `resources/views/admin`
- Use Laravel policies for fine-grained access

## Best Practices
- Use RESTful routes and controllers
- Separate single and bulk actions
- Use config files for settings
- Keep UI components modular (partials, Blade includes)
- Use AJAX for a modern UX
- Write clear, maintainable code and comments

## Deployment Guide
1. Set up a production server (Linux recommended)
2. Install PHP, Composer, Node.js, and a web server (Nginx/Apache)
3. Clone the repo and set up `.env` for production
4. Run `composer install --optimize-autoloader --no-dev`
5. Run `npm install && npm run build` for assets
6. Run `php artisan migrate --force`
7. Run `php artisan config:cache && php artisan route:cache && php artisan view:cache`
8. Set correct permissions for `storage` and `bootstrap/cache`
9. Set up a queue worker and scheduler if needed
10. Use HTTPS and secure your `.env`

---

For more details, see the code comments and each module's README (if present). Share this documentation with your team for onboarding and scaling!
