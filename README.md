<p align="center"><img src="https://horizom.github.io/img/horizom-logo-color.svg" width="400"></p>

<p align="center">
<a href="https://packagist.org/packages/horizom/app"><img src="https://poser.pugx.org/horizom/app/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/horizom/app"><img src="https://poser.pugx.org/horizom/app/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/horizom/app"><img src="https://poser.pugx.org/horizom/app/license.svg" alt="License"></a>
</p>

> **The lightness PHP framework** — fast, minimal, PSR-compliant.

---

## Overview

Horizom is a lightweight PHP micro-framework built on PSR standards. It provides a clean, expressive foundation for building web applications and REST APIs without the overhead of a full-stack framework.

**Core packages:**

| Package           | Role                                            |
| ----------------- | ----------------------------------------------- |
| `horizom/core`    | Application container, configuration, lifecycle |
| `horizom/routing` | PSR-15 router powered by FastRoute              |
| `horizom/http`    | PSR-7 request/response helpers                  |

---

## Requirements

- PHP **8.0** or higher
- [Composer](https://getcomposer.org/) 2.x

---

## Installation

```bash
composer create-project horizom/app my-project
cd my-project
cp .env.example .env
```

Start the built-in development server:

```bash
composer serve
# → http://localhost:8000
```

---

## Project Structure

```
├── app/
│   ├── Controllers/       # Request handlers (PSR-7)
│   ├── Middlewares/       # PSR-15 middleware (CORS, errors…)
│   ├── Models/            # Domain models
│   └── Providers/         # Service providers
├── bootstrap/
│   ├── app.php            # Application bootstrap
│   └── dependencies.php   # Middleware registration
├── config/
│   ├── app.php            # App settings
│   ├── auth.php           # JWT / auth settings
│   └── database.php       # Database connections
├── public/
│   └── index.php          # Front controller
├── resources/
│   └── views/             # Blade templates
├── routes/
│   ├── web.php            # Web routes
│   └── api.php            # API routes
└── tests/
    └── Unit/              # PHPUnit test suites
```

---

## Configuration

All sensitive values must be set in your **`.env`** file and are never committed to version control.

```dotenv
# Application
APP_NAME=Horizom
APP_ENV=development
APP_URL=http://localhost:8000
APP_DEBUG=false

# Database
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=horizom
DB_USERNAME=root
DB_PASSWORD=
```

Access config values anywhere with the `config()` helper:

```php
$name = config('app.name');         // "Horizom"
$dsn  = config('database.default'); // "development"
```

---

## Routing

### Web routes (`routes/web.php`)

```php
$router->get('/', 'MainController@index');
```

### API routes (`routes/api.php`)

```php
$router->group(['prefix' => 'api'], function (RouteCollector $router) {
    $router->get('/status',  'ApiController@status');
    $router->get('/version', 'ApiController@version');
});
```

Use explicit HTTP verbs — avoid `any()` as it widens the attack surface.

---

## Controllers

Controllers are **`final`** classes. They return a PSR-7 `ResponseInterface`.

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

final class ApiController
{
    public function status(): ResponseInterface
    {
        return response()->json(['status' => 'UP']);
    }
}
```

---

## Middleware

### Registering middleware

Global middleware is registered in `bootstrap/dependencies.php`:

```php
$app->add(new \App\Middlewares\CorsMiddleware());
```

### CORS (`CorsMiddleware`)

Handles preflight `OPTIONS` requests directly — returns **204** without invoking the next handler — and attaches CORS headers to all other responses.

To restrict allowed origins in production, edit the `$allowedOrigins` property:

```php
// app/Middlewares/CorsMiddleware.php
private array $allowedOrigins = [
    'https://myapp.com',
    'https://www.myapp.com',
];
```

### Error handling (`ErrorHandlerMiddleware`)

Maps exceptions to structured HTTP responses:

| Exception                   | Status                      |
| --------------------------- | --------------------------- |
| `NotFoundException`         | `404 Not Found`             |
| `MethodNotAllowedException` | `405 Method Not Allowed`    |
| Any other `Throwable`       | `500 Internal Server Error` |

XHR / AJAX requests receive a JSON payload; browser requests receive an HTML error page.

> Stack traces are **only included** when `app.pretty_debug = true`. Never expose them in production.

---

## Built-in API Endpoints

| Method | Path           | Description                   |
| ------ | -------------- | ----------------------------- |
| `GET`  | `/api`         | Health check alias            |
| `GET`  | `/api/status`  | Returns `{ "status": "UP" }`  |
| `GET`  | `/api/version` | Returns the framework version |

---

## Views

Templates use the **Blade** engine. Layouts live in `resources/views/`.

```blade
@extends('default')

@section('content')
    <h1>Hello, World!</h1>
@endsection
```

Available helpers inside templates:

```blade
{{ config('app.name') }}
{{ asset('css/style.css') }}
```

---

## Testing

Tests use [PHPUnit](https://phpunit.de/) 11 and live in `tests/Unit/`.

```bash
composer test
# or directly:
vendor/bin/phpunit
```

```
tests/
└── Unit/
    ├── Controllers/
    │   └── ApiControllerTest.php
    └── Middlewares/
        ├── CorsMiddlewareTest.php
        └── ErrorHandlerMiddlewareTest.php
```

---

## Security

| Concern                     | Mitigation                                      |
| --------------------------- | ----------------------------------------------- |
| Secrets in source code      | Use `.env` for all credentials — never hardcode |
| Stack trace exposure        | Only shown when `app.pretty_debug = true`       |
| CORS wildcard in production | Replace `['*']` with an explicit origin list    |
| Over-broad HTTP verbs       | API routes use `GET` only; avoid `any()`        |
| Type safety                 | All classes declare `strict_types=1`            |

---

## Learning Horizom

The full [Horizom documentation](https://horizom.github.io) covers installation, routing, middleware, templating, and more.

## License

The Horizom framework is open-sourced software licensed under the [MIT license](LICENSE).
