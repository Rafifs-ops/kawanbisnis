---
paths:
  - '*.{json,neon}'
  - composer.json
---

# General

## Code Quality Tools Configuration
Pint config: `{"preset": "laravel"}`. PHPStan: level 7, analyses `app/`, `bootstrap/`, `config/`, `database/`, `routes/`. Includes Larastan and Carbon extensions. Run `composer lint:check` for Pint dry run, `composer types:check` for PHPStan. `composer test` runs config:clear, lint:check, types:check, then tests sequentially.

## Composer Scripts Convention
Key scripts: `setup` (install, key, migrate, npm build), `dev` (concurrently server+queue+vite via npx), `lint` (pint --parallel), `lint:check` (pint --parallel --test), `types:check` (phpstan analyse), `test` (config:clear → lint:check → types:check → artisan test). Use `Composer\Config::disableProcessTimeout` for long-running scripts.
