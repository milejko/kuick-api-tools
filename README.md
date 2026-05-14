# Kuick API Tools
[![Latest Version](https://img.shields.io/github/release/milejko/kuick-api-tools.svg?cacheSeconds=3600)](https://github.com/milejko/kuick-api-tools/releases)
[![PHP](https://img.shields.io/badge/PHP-8.3%20|%208.4%20|%208.5-blue?logo=php&cacheSeconds=3600)](https://www.php.net)
[![Total Downloads](https://img.shields.io/packagist/dt/kuick/api-tools.svg?cacheSeconds=3600)](https://packagist.org/packages/kuick/api-tools)
[![GitHub Actions CI](https://github.com/milejko/kuick-api-tools/actions/workflows/ci.yml/badge.svg)](https://github.com/milejko/kuick-api-tools/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/milejko/kuick-api-tools/graph/badge.svg?token=80QEBDHGPH)](https://codecov.io/gh/milejko/kuick-api-tools)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?cacheSeconds=14400)](LICENSE)

## API Tools for Kuick Framework

This package plugs into the [Kuick Framework](https://github.com/milejko/kuick-framework) and
automatically exposes three HTTP endpoints:

| Endpoint | Method | Description |
|---|---|---|
| `/api/doc` | GET | Swagger UI (HTML) |
| `/api/doc.json` | GET | OpenAPI specification (JSON) |
| `/api/ops` | GET | System diagnostics — protected by Bearer token |

## Requirements

- PHP 8.2+
- [Kuick Framework](https://github.com/milejko/kuick-framework) ^2.8

## Installation

```bash
composer require kuick/api-tools
```

The Kuick Framework auto-discovers the route, guard, and DI config files bundled in this package —
no manual wiring is required.

## Endpoints

### `GET /api/doc`

Returns a self-contained Swagger UI page that loads the OpenAPI spec from `/api/doc.json`.
No authentication required.

### `GET /api/doc.json`

Returns the OpenAPI specification as JSON. The spec is built at runtime by scanning
`#[OA\...]` attributes in your project's `src/` directory (via `zircote/swagger-php`).
No authentication required.

### `GET /api/ops`

Returns a JSON object with full system diagnostics:

```json
{
  "request":              { ... },
  "environment":          { ... },
  "di-config":            { ... },
  "opcache-status":       { ... },
  "apcu-status":          { ... },
  "php-version":          "8.3.x",
  "php-config":           { ... },
  "php-loaded-extensions": "..."
}
```

**Protected by Bearer token** — see [Configuration](#configuration) below.

## Configuration

All settings are read from environment variables. Set them in your `.env` file or deployment environment.

### Ops endpoint security

| Environment variable | DI key | Default | Description |
|---|---|---|---|
| `API_SECURITY_OPS_GUARD_TOKEN` | `api.security.ops.guard.token` | *(empty)* | Bearer token required to access `/api/ops`. An empty value means **all requests are rejected**. |

Example — calling the protected endpoint:

```bash
curl -H "Authorization: Bearer my-secret-token" https://your-app.example.com/api/ops
```

### OpenAPI metadata

| Environment variable | DI key | Default | Description |
|---|---|---|---|
| `API_OPENAPI_TITLE` | `api.openapi.title` | `Kuick API Tools` | Title shown in the Swagger UI and spec |
| `API_OPENAPI_DESCRIPTION` | `api.openapi.description` | `Kuick API Tools is a set of tools for building APIs with the Kuick framework.` | Description shown in the spec |
| `API_OPENAPI_VERSION` | `api.openapi.version` | `3.1.0` | Version string shown in the spec |

Example `.env` snippet:

```dotenv
API_SECURITY_OPS_GUARD_TOKEN=my-secret-token

API_OPENAPI_TITLE=My Project API
API_OPENAPI_DESCRIPTION=Internal REST API for My Project
API_OPENAPI_VERSION=1.0.0
```

## Annotating your API with OpenAPI attributes

`DocJsonController` scans `$projectDir/src` for [swagger-php](https://zircote.github.io/swagger-php/)
attributes at runtime. Decorate your controllers with `#[OA\...]` attributes to include
them in the generated spec:

```php
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/users',
    description: 'Returns a list of users',
    tags: ['Users'],
    responses: [
        new OA\Response(response: 200, description: 'List of users', content: new OA\JsonContent()),
    ]
)]
final class UserListController
{
    public function __invoke(): JsonResponse { ... }
}
``` 
