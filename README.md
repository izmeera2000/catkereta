<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Car paint bookings

This application uses the DashLite template for a car-paint booking workflow:

- Customers request a paint service and preferred date from the home page.
- Shop staff review requests, update booking statuses, and upload before/during/after photos.
- Customers view booking progress using the private signed link shown after submitting a request.
- Customers can also track a booking using its service number from the home page.
- Progress photos are resized to a maximum 2000-pixel edge and compressed to WebP when that reduces their size. They are stored on Laravel's private local disk, outside the public web directory.

### Run locally

The application targets PHP 8.2.28 and Laravel 12.

```bash
php artisan migrate
php artisan serve
```

To create a staff account with the standard seeder, set `STAFF_USER_NAME`, `STAFF_USER_EMAIL`, and `STAFF_USER_PASSWORD` in `.env`. Use a password of at least 12 characters, then run:

```bash
php artisan db:seed
```

`DatabaseSeeder` calls `StaffUserSeeder`, which assigns the `staff` role to the configured email and does not reset an existing account's password. Alternatively, `php artisan make:staff "Shop Manager" manager@example.com` prompts for a password interactively. Sign in at `/staff/login`; customer booking requests start at `/`.

Every booking has a randomly generated service number. Customers can enter it on the home page to reopen their progress page. Treat the number as private: anyone who has it can view that booking's details and photos.

### Temporary cPanel migration route

If cPanel does not provide terminal access, temporarily set `MIGRATIONS_ROUTE_ENABLED=true` in `.env`, clear the configuration cache if one is active, and visit `/maintenance/migrate`. Submit the form to apply pending migrations. This page is publicly accessible while enabled, so set the value back to `false` and clear/rebuild the configuration cache immediately after use. The route is disabled by default, accepts POST only for the migration action, and is rate limited. Prefer `php artisan migrate --force` from cPanel Terminal when available.

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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
