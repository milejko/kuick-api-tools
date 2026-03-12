# Kuick API Tools — Copilot Instructions

## Project Overview

PHP library (`kuick/api-tools`) that plugs into the Kuick Framework to expose three HTTP endpoints:
- `GET /api/doc` — Swagger UI (HTML)
- `GET /api/doc.json` — OpenAPI JSON (scans `src/` at runtime via `zircote/swagger-php`)
- `GET /api/ops` — System diagnostics (env, DI config, opcache, APCu, PHP info), protected by bearer token

Requires PHP 8.2+. Targets PHP 8.3, 8.4, and 8.5 in CI.

## Commands

```bash
# Run full quality suite (CS, static analysis, mess detection, tests with coverage)
composer test:all

# Individual checks
composer test:phpcs          # PSR-12 code style
composer test:phpstan        # Static analysis (level 5)
composer test:phpmd          # Mess detection
composer test:phpunit        # Unit tests with coverage

# Auto-fix code style
composer fix:phpcbf

# Run a single test file
XDEBUG_MODE=coverage vendor/bin/phpunit tests/UI/DocHtmlControllerTest.php

# Run a single test method
XDEBUG_MODE=coverage vendor/bin/phpunit --filter testIfAllValuesAreReturned

# Docker-based full test run (used in CI)
make test
```

## Architecture

Four `final` classes, each invokable (`__invoke`), wired together by config files:

```
src/
  UI/
    DocHtmlController.php   # Returns hardcoded Swagger UI HTML; no dependencies
    DocJsonController.php   # Injects app.projectDir, scans /src for OAA attributes
    OpsController.php       # Injects DI Container, dumps full system diagnostics
  Security/
    OpsGuard.php            # Injects api.security.ops.guard.token, validates Bearer header

config/
  api-tools.routes.php      # Registers RouteConfig entries for all 3 endpoints
  api-tools.guards.php      # Registers GuardConfig for /api/ops(.+)? → OpsGuard
  di/
    api-tools.di.php        # Autowires all 4 classes
    api-tools.optimization.php  # (optimization hints)
```

Routes and guards are declared as arrays of `RouteConfig`/`GuardConfig` value objects returned from config files — the Kuick Framework auto-discovers these.

`OpsGuard` receives `api.security.ops.guard.token` from DI, which reads the `API_SECURITY_OPS_GUARD_TOKEN` env var (defaults to empty string).

## Key Conventions

**All classes are `final`** — do not remove `final` when adding new classes; it is intentional.

**Invokable pattern** — controllers and guards expose only `__invoke()`. No other public methods.

**OpenAPI attributes on classes** — use `#[OAA\Get(...)]` / `#[OAA\SecurityScheme(...)]` attributes directly on the class, not on methods. `DocJsonController` scans `$projectDir/src` at runtime to collect these.

**DI injection via `#[Inject('key')]`** — scalar values (strings) are injected with `#[Inject('di.key')]` on constructor parameters. Objects use plain autowiring.

**PSR-12 strictly enforced** — run `composer fix:phpcbf` before committing.

**File header** — every PHP file begins with the standard copyright/license block:
```php
/**
 * Kuick API Tools (https://github.com/milejko/kuick-api-tools)
 *
 * @link       https://github.com/milejko/kuick-api-tools
 * @copyright  Copyright (c) 2010-2026 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-api-tools?tab=MIT-1-ov-file#readme New BSD License
 */
```

**Test namespaces** — `Tests\Unit\Kuick\ApiTools\` mirrors `Kuick\ApiTools\` (e.g., `tests/UI/DocHtmlControllerTest.php`).

**Coverage metadata required** — every test class must declare `#[CoversClass(TargetClass::class)]`; PHPUnit is configured with `requireCoverageMetadata="true"` and `beStrictAboutCoverageMetadata="true"`.

**PSR-7 in tests** — use `Nyholm\Psr7\ServerRequest` directly (no mocking framework needed for HTTP objects).

**`@SuppressWarnings(PHPMD)`** — used sparingly only where PHPMD raises unavoidable false positives (e.g., the OpenAPI scanner call in `DocJsonController`).
