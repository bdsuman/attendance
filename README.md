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
   composer dump-autoload
   npm install
   npm run build
   ```
4. Run migrations & seeders (adjust DB connection in `.env`):
   ```bash
   php artisan migrate --force
   php artisan db:seed
   ```
5. Start the dev server:
   ```bash
   php artisan serve
   ```

## Common composer troubleshooting
- If `composer update` reports PHP version conflicts, run:
  ```bash
  composer config platform.php 8.2.0
  composer update --with-all-dependencies
  ```
- If you prefer to regenerate the lockfile from scratch (destructive):
  ```bash
  rm composer.lock
  rm -rf vendor
  composer config platform.php 8.2.0
  composer update --with-all-dependencies
  ```

## Key files & behavior
- `app/Helpers/TimeHelper.php` — `convert_time_to_seconds()`, `seconds_to_hhmm()` helpers.
- `app/Models/Shift.php` — stores `start_time` / `end_time` in seconds. The model converts HH:MM inputs to seconds on save.
- `app/Http/Resources/ShiftResource.php` — exposes `start_time`/`end_time` as `HH:MM` and minute-based fields as minutes.
- `app/Models/Attendance.php` — computes and persists derived fields via `computeCalculatedFields()` and a Model Observer.
- `app/Observers/AttendanceObserver.php` — ensures computed fields persist whenever attendances change.
- `app/Http/Controllers/AttendanceController.php` — single status-driven endpoint to handle `check_in`, `break_in`, `break_out`, and `check_out` actions (see code comments for rules and behavior).
- `app/Http/Resources/AttendanceResource.php` — returns both raw datetimes and user-friendly HH:MM / computed fields for API consumers.

## Important notes about negative minutes
- The code clamps `late_minutes` and `early_leave_minutes` to non-negative integers and formats times correctly. If you see negative values in the DB, sanitize:
  ```sql
  UPDATE attendances
  SET late_minutes = GREATEST(0, late_minutes),
      early_leave_minutes = GREATEST(0, early_leave_minutes);
  ```

## API & Documentation
- API controllers contain OpenAPI annotations. The project previously used L5‑Swagger to generate UI views. To (re)generate docs locally:
  ```bash
  php artisan l5-swagger:generate
  ```
- A Redoc view is included in `resources/views/redoc.blade.php` (if published). See the `resources/docs/examples/` folder for sample request bodies.

## Tests
- Basic PHPUnit and feature tests live in `tests/`. Run tests with:
  ```bash
  php artisan test
  ```

## Development notes
- Use `AttendanceResource` and `ShiftResource` for API responses to ensure consistent formatting.
- The `computeCalculatedFields()` method compares attendance timestamps (Carbon) to shift start/end calculated by adding seconds from the day's midnight. Shift times and grace periods are stored as seconds in the DB; the resources convert these values for presentation.

## Contribution
- Fork, create a branch, make changes, run tests, and open a PR. Keep changes small and target a single behavior change per PR.

## Quick commands summary
```bash
composer install
composer dump-autoload
composer config platform.php 8.2.0   # if CLI PHP differs
php artisan migrate --force
php artisan db:seed
php artisan l5-swagger:generate   # if using L5-Swagger
php artisan serve
```

## Contact / Submission email
- See the `EMAIL_SUBMISSION.md` section below for a ready subject and body to use when submitting this repository.

---

**Submission email (use as-is or adapt):**

Subject: Submit: Attendance (Laravel) — bdsuman

Body:

Hello,

Please find attached/published the Attendance application repository (Laravel) prepared by me for submission.

Summary:
- Small Laravel app to manage employees, shifts and attendance entries.
- Shift times are stored in seconds; helpers convert to/from HH:MM.
- Single status-driven attendance endpoint (check_in, break_in, break_out, check_out).
- Computed attendance metrics are persisted (worked_seconds, worked_hours, calculated_status, late_minutes, early_leave_minutes).

How to run locally:
1. Copy `.env.example` → `.env` and set DB credentials.
2. Ensure PHP 8.2 is used or instruct Composer to resolve for 8.2: `composer config platform.php 8.2.0`.
3. Run `composer install`, `php artisan migrate --seed`, and `php artisan serve`.

Notes:
- If composer reports conflicts due to packages previously resolved on PHP 8.4, run `composer config platform.php 8.2.0` and then `composer update --with-all-dependencies` to re-resolve for PHP 8.2.
- If any negative values exist in the `attendances` table for `late_minutes` or `early_leave_minutes`, sanitize them with the SQL provided in the README.

If you need me to run additional tests, provide a deployment task, or change the documentation style (Scribe vs L5‑Swagger), tell me which option and I will prepare the changes.

Best regards,
bdsuman
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
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
