# Attendance

A lightweight Laravel-based attendance system. This repository contains models, controllers, resources, and API documentation scaffolding for managing employees, shifts and daily attendance entries.

## Quick summary
- Framework: Laravel (app skeleton present)
- PHP: Project targets PHP ^8.2 (see `composer.json`)
- Key features:
  - Time helpers to convert HH:MM / minutes ↔ seconds
  - Shift model stores times in seconds; `ShiftResource` presents HH:MM
  - Attendance flow consolidated into a status-driven endpoint (check_in/check_out/break_in/break_out)
  - Computed attendance fields persisted (worked_seconds, worked_hours, calculated_status, late_minutes, early_leave_minutes)
  - Observer to compute/persist attendance derived fields
  - API docs via OpenAPI annotations and L5‑Swagger (published view + Redoc wrapper)

## Requirements
- PHP 8.2+ (project expects `^8.2` in `composer.json`)
- Composer
- MySQL or other DB driver supported by Laravel (configured in `.env`)

Note: Some lockfile entries in `composer.lock` may reference packages resolved on PHP 8.4. If your local CLI PHP is not 8.2 you can tell Composer to resolve for 8.2 (see Setup below).

## Setup (local)
1. Copy the environment file and set DB credentials:
   ```bash
   cp .env.example .env
   # edit .env to set DB and APP_URL as required
   ```
2. Ensure your CLI PHP is compatible, or make Composer resolve as PHP 8.2:
   ```bash
   php -v
   composer config platform.php 8.2.0
   ```
3. Install PHP dependencies and assets:
   ```bash
   composer install --prefer-dist
## Project Workflow (Developer & Deployment)

This section documents the recommended workflow for developing, testing, releasing and deploying this project. It is intentionally focused on repo-specific practices and commands — not general Laravel documentation.

1. Branching & PR flow
  - `main`: stable production code only. Protected branch; require PR reviews and passing CI.
  - `develop` or feature branch base (optional): integration branch used for ongoing work.
  - Feature branches: create from `develop` or `main` using `feature/<short-description>`.
  - Hotfix branches: `hotfix/<ticket-or-issue>` created from `main` to patch production quickly.
  - Workflow: open a PR against `develop` (or `main` if you use trunk-based flow), assign reviewers, and include testing steps in the PR description.

2. Local development checklist
  - Create a feature branch: `git checkout -b feature/your-feature`.
  - Install dependencies:
    ```bash
    composer install
    composer dump-autoload
    npm install
    npm run dev    # or `npm run build` for production assets
    ```
  - Set up environment: copy `.env.example` to `.env` and set DB + app settings.
  - Run local migrations and seeders (dev DB recommended):
    ```bash
    php artisan migrate
    php artisan db:seed
    ```
  - Run factories for test data when needed.

3. Coding standards & checks
  - PHP formatting: use `php artisan pint` (pint is included in `require-dev` here).
  - Static analysis: run any project static tools you prefer (e.g., phpstan/psalm) if added.
  - JS lint/format: `npm run lint` / `npm run format` if configured.
  - Test locally before PR:
    ```bash
    php artisan test
    ```

4. API changes & docs
  - Add OpenAPI annotations to controllers where needed.
  - Regenerate documentation locally (if using L5‑Swagger):
    ```bash
    php artisan l5-swagger:generate
    ```
  - If switching to Scribe (optional), run scribe generation after updating composer and config.

5. Continuous Integration (recommended)
  - CI should run on every PR and include:
    - `composer install --prefer-dist --no-interaction`
    - `composer dump-autoload --no-interaction`
    - Run tests: `php artisan test`
    - Run lint/format checks
    - (Optional) Build front-end assets (`npm ci && npm run build`) if you want to test asset generation in CI
  - Example: a minimal GitHub Actions job would install PHP, install composer packages, run tests, and report results.

6. Releasing & deployment (recommended manual checklist)
  - Tag a release in git: `git tag -a vX.Y.Z -m "Release vX.Y.Z"` and `git push origin --tags`.
  - On the deploy target (or deployment script):
    ```bash
    # pull tag or branch
    git fetch --tags && git checkout vX.Y.Z

    # install PHP deps
    composer install --no-dev --prefer-dist --optimize-autoloader

    # compile assets (if not prebuilt)
    npm ci && npm run build

    # put the app in maintenance mode (optional for downtime deployments)
    php artisan down --message="Deploying vX.Y.Z"

    # run migrations
    php artisan migrate --force

    # clear/refresh caches
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # restart queues & workers
    php artisan queue:restart

    # bring the app back up
    php artisan up
    ```

7. Zero-downtime & rollback guidance
  - For zero-downtime, prefer rolling deploys that run migrations in a way that is backward compatible (avoid destructive migration operations that break old code). If you must run breaking migrations, coordinate deploy and workers, and use maintenance mode.
  - Rollback a release:
    - If a DB migration must be reverted, run `php artisan migrate:rollback` on the deploy target (only if safe). Ideally restore DB from backup if data-destructive changes occurred.
    - To revert code, checkout the previous tag and restart services.

8. Backups & data safety
  - Before running migrations on production, take a DB backup (dump or snapshot).
  - Keep a backup retention policy and test restores occasionally.

9. Monitoring & logs
  - Ensure log rotation is configured for `storage/logs` and your process manager (Supervisor, systemd) is restarting workers when needed.
  - Add health checks for the app (simple `/health` route) and monitor response times.

10. Security & secrets
  - Do NOT commit `.env` — use secret manager or CI encrypted secrets for production credentials.
  - Rotate keys and credentials periodically.

11. PR / Code review checklist
  - Does the branch have a descriptive name and meaningful commit messages?
  - Are tests added/updated for new behavior? Do all tests pass locally?
  - Is code formatted and linted? Does `php artisan pint` pass?
  - Are environment/config changes documented and safe for production?

12. Automation & future improvements
  - Consider adding GitHub Actions workflows that: run tests, generate docs, and publish a `release` artifact (e.g., a prebuilt build or docker image).
  - Consider adding an automated DB sanitization migration to avoid negative `late_minutes` if required.

If you want, I can add a sample CI workflow file (GitHub Actions) and/or a small `deploy.sh` script that automates the release checklist.
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
