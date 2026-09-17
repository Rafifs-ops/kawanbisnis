---
paths:
  - app/Providers/AppServiceProvider.php
---

# Providers

## AppServiceProvider Configuration
AppServiceProvider `boot()` calls `$this->configureDefaults()` which: 1) Sets `Date::use(CarbonImmutable::class)`. 2) `DB::prohibitDestructiveCommands(app()->isProduction())`. 3) Configures `Password::defaults()` with stricter rules in production (min 12, mixedCase, letters, numbers, symbols, uncompromised) and null in non-production.
