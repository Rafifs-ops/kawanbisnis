# Project: KawanBisnis

Laravel Livewire platform for UMKM AI growth diagnosis. User-facing copy and AI system prompts are Bahasa Indonesia; write new code comments/PHPDoc in English.

## Essential Commands

```bash
composer setup          # Full setup: install deps, migrate, build assets
composer dev            # Start dev server + queue + Vite (needs Node 22+)
composer test           # Run CI: lint → types → test (order matters)
composer lint           # Fix code style with Pint
composer types:check    # Run PHPStan level 7
```

After editing PHP: `vendor/bin/pint --dirty --format agent`

Single test: `php artisan test tests/Feature/SomeTest.php` or `--filter=testName`

## Critical Architecture Notes

- **No REST API** - All UI is full-page Livewire via `Route::livewire()`
- **AI Pipeline** - Queued job (`ProcessGrowthDiagnosisJob`, 3 tries, 300s timeout) runs 4 agents sequentially (Analytics → Customer → Marketing → Strategy). Job clears prior results first (idempotent retries).
- **Admin Panel** (`/admin`) - Only accessible when `users.is_admin = true`. Not mass-assignable. Promote via:
  ```bash
  php artisan tinker
  User::where('email', $x)->update(['is_admin' => true])
  ```
- **RAG System** - `AgentKnowledge` stores 2048-dim embeddings (`nvidia/nemotron-3-embed-1b:free`). Feedback loop: "Tandai Selesai" saves check-ins as AgentKnowledge.
- **Auth** - Fortify (email/passkeys) + Google OAuth (`app/Http/Controllers/Auth/GoogleController.php`)
- **Filament** - `composer install/update` triggers `filament:upgrade`. Resources in `app/Filament/Resources/`.

## Key Gotchas

- `composer test` fails if lint/types fail first - fix with `composer lint`
- Vite manifest missing? Run `composer setup` or `composer dev`
- `composer dev` requires Node.js 22+ (uses `npx concurrently`)
- **AI in tests is always faked** - Never call real OpenRouter. Use `AnalyticsAgent::fake([...])` etc.
- **Build tool is vite-plus** - Use `npm run dev`/`build` (or `vp dev`/`build`), not bare `vite` CLI
- `.npmrc` sets `ignore-scripts=true` - postinstall scripts skipped on npm install
- EditorConfig: 4-space, LF, UTF-8
- Tests use in-memory SQLite (`DB_DATABASE=:memory:`) with `QUEUE_CONNECTION=sync`

## Important Files & Conventions

- AI configuration: `config/ai.php` (single source of truth for model names)
- Laravel Boost skills: `.agents/skills/` (regenerated via `php artisan boost:update`)
- MCP server: `opencode.json` (`php artisan boost:mcp`)
- Path-scoped rules: `.ai/rules/` (auto-injected when editing matching files)
- Tests: `tests/Pest.php` applies `RefreshDatabase` to Feature tests only (Unit tests touching DB will fail)
- Factories: `database/factories/` for all domain models
- AI Agents: `app/AI/Agents/` (AnalyticsAgent, CustomerAgent, MarketingAgent, StrategyAgent)
- AI Responses: `app/AI/Responses/` (typed result objects)