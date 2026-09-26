# Project: KawanBisnis

Laravel 13 + Livewire 4 (Flux UI) platform for UMKM AI growth diagnosis. User-facing copy and AI prompts are Bahasa Indonesia (`lang/id.json`); write new code comments/PHPDoc in English.

## Essential Commands

```bash
composer setup       # deps + .env + key + migrate + npm install + build
composer dev         # serve + queue:listen + Vite (needs Node 22+)
composer test        # config:clear -> lint:check -> phpstan (lvl 7) -> pest
composer ci:check    # tests only (what CI runs)
composer lint        # fix style with Pint
composer types:check # PHPStan level 7
```

- `composer test` runs Pint in *check* mode, so it fails before tests if style or types fail. Fix style with `composer lint`.
- Single test: `php artisan test tests/Feature/SomeTest.php` or `--filter=testName`.
- After editing PHP: `vendor/bin/pint --dirty --format agent`.

## Architecture Notes

- **No REST API** - All UI is full-page Livewire via `Route::livewire()` (`routes/web.php`). Components in `app/Livewire/`; Filament admin in `app/Filament/Resources/`.
- **AI pipeline** - `ProcessGrowthDiagnosisJob` (3 tries, 300s timeout) runs 4 agents sequentially Analytics → Customer → Marketing → Strategy. It deletes prior `AgentAnalysis`/`ActionPlan` first, so retries are idempotent. Each agent returns raw JSON text; the job calls `json_decode` and expects a JSON object.
- **AI config is `config/ai.php`** - single source of truth for model names (OpenRouter text `openrouter/free`, embeddings `nvidia/nemotron-3-embed-1b:free`, 2048-dim).
- **RAG** - `AgentKnowledge` stores per-agent-type embeddings. Marking an action plan "Tandai Selesai" saves the check-in as strategy knowledge, feeding future diagnoses.
- **Admin** (`/admin`) - requires `users.is_admin = true`, which is not mass-assignable. Promote via `User::where('email', $x)->update(['is_admin' => true])`.
- **Auth** - Fortify (passkeys + 2FA) + Google OAuth (`app/Http/Controllers/Auth/GoogleController.php`).

## Gotchas

- **AI is always faked in tests** - never call real OpenRouter. Use `AnalyticsAgent::fake([...])` etc. No `OPENROUTER_API_KEY` needed.
- **Build tool is vite-plus** - use `npm run dev`/`build` (or `vp dev`/`build`), not bare `vite`.
- `.npmrc` sets `ignore-scripts=true`, so npm postinstall scripts are skipped.
- Feature tests get `RefreshDatabase` automatically (`tests/Pest.php`); Unit tests do not, so DB-touching Unit tests fail. Tests run sqlite `:memory:` with queue/cache/mail arrays (`phpunit.xml`).
- Root-level `kawan-bisnis*` files are local libsql/Turso DB artifacts - do not commit them. `.ai/`, `.agents/`, and `.github/` are gitignored (Boost-generated).
- `filament:upgrade` runs on composer autoload dump; `boost:update` runs on `composer update`.

## Key Files

- AI agents / responses: `app/AI/Agents/` (`AnalyticsAgent`, `CustomerAgent`, `MarketingAgent`, `StrategyAgent`), `app/AI/Responses/`
- Factories exist for every domain model in `database/factories/`.
- MCP server for Boost: `opencode.json` (`php artisan boost:mcp`); skills configured in `boost.json`.
