# Project: KawanBisnis

Laravel Livewire business platform. Some files use Indonesian-language comments (e.g., `GoogleController`).

## Stack

- **Backend**: Laravel 13.31, PHP 8.5+, Livewire 4.4, Fortify 1.39, Socialite 5.31
- **Admin panel**: Filament 5.8 (at `/admin`)
- **Frontend**: Flux UI 2.19, Tailwind CSS 4, Vite 8, vite-plus (`vp build`/`vp dev`, not `vite`)
- **Testing**: Pest 5.1, Larastan level 7, Pint (laravel preset)
- **Database**: SQLite default (tests use `:memory:`)
- **AI**: Laravel AI 0.11.2, OpenRouter only — text: `nex-agi/nex-n2.5-pro:free`, embeddings: `nvidia/nemotron-3-embed-1b:free` (2048-dim)
- **Extra packages**: spatie/laravel-medialibrary, spatie/laravel-tags, laravel/chisel

## Quick Commands

```bash
composer setup          # Install deps, generate key, migrate, npm build
composer dev            # Start server + queue + vite concurrently (via npx)
composer test           # config:clear → lint:check → types:check → test (sequential)
composer ci:check       # Alias for composer test (used in CI)
composer lint           # pint --parallel
composer lint:check     # pint --parallel --test (dry run)
composer types:check    # phpstan analyse (level 7)
```

CI runs `composer ci:check` after `composer setup`. The order `lint → types → tests` is mandatory.

## Testing

```bash
# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with filter
php artisan test --filter=testName

# Pest direct
vendor/bin/pest tests/Feature/ExampleTest.php

# Create new test (Pest)
php artisan make:test --pest SomeFeatureTest
```

- Tests use in-memory SQLite (configured in `phpunit.xml`).
- `RefreshDatabase` is auto-applied to all Feature tests via `tests/Pest.php`.
- Factories in `database/factories/`.

## Code Style

- Run `vendor/bin/pint --dirty --format agent` after modifying any PHP file.
- Pint config: `pint.json` (laravel preset).
- EditorConfig: 4-space indent, UTF-8, LF line endings.

## Architecture

### Domain Models

Beyond `User`, the app has domain models: `BusinessPassport`, `BusinessSnapshot`, `GrowthGoal`, `GrowthDiagnosis`, `ActionPlan`, `CheckInFeedback`, `AgentAnalysis`, `AgentKnowledge`.

### Livewire

- Components: `app/Livewire/` — subfolders `Dashboard/`, `Settings/`, `Landing/`, `Actions/`.
- Routes use `Route::livewire()` syntax (see `routes/settings.php`).
- Flux UI components for UI (`flux:`, `Flux::toast()`).
- Alpine.js for client-side interactions.

### Filament

- Admin panel at `/admin` via Filament 5 (`AdminPanelProvider` at `app/Providers/Filament/AdminPanelProvider.php`).
- Custom resource: `AgentKnowledgeResource` at `app/Filament/Resources/AgentKnowledge/`.

### Authentication

- Fortify handles auth flows (login, register, password reset, 2FA, passkeys).
- Google OAuth via Socialite at `app/Http/Controllers/Auth/GoogleController.php` (note: `Controllers/Auth/`, not just `Auth/`).
- Passkey support enabled (WebAuthn). Discovery endpoint: `.well-known/passkey-endpoints`.
- User model implements `PasskeyUser` interface.

### MCP / AI

- Laravel Boost MCP server runs via `php artisan boost:mcp` (configured in `opencode.json`).
- `config/ai.php` configures 16 AI providers; default is OpenRouter, images default to OpenRouter.

## Key Files

- `app/Models/User.php` — Laravel 13 attributes (`#[Fillable]`, `#[Hidden]`), nullable password for OAuth users.
- `app/Concerns/ProfileValidationRules.php` — Reusable validation trait.
- `routes/settings.php` — Livewire route pattern example.
- `tests/Pest.php` — Pest configuration (RefreshDatabase, custom expectations).
- `phpstan.neon` — Level 7, analyses `app/`, `bootstrap/`, `config/`, `database/`, `routes/`.

## Gotchas

- `composer test` runs lint and types before tests — do not skip steps.
- `composer dev` uses `npx concurrently` — requires Node.js.
- Vite manifest error: run `composer setup` or `composer dev` first.
- `google_id` column in users table is nullable; password is nullable for OAuth-only users.

## Convention Rules

- `.ai/rules/index.md` maps file globs to convention docs (models, tests, livewire, auth, etc.)
- `.agents/skills/` has OpenCode skills for Flux UI, Livewire, Tailwind, testing, etc.
- AI prompts and user-facing messages use Bahasa Indonesia (e.g., `UMKM` context).
- Technical code comments and PHPDoc remain in English.
