---
paths:
  - 'tests/**/*.php'
---

# Tests

## Pest Testing Conventions
Tests use Pest syntax. `tests/Pest.php` configures `pest()->extend(TestCase::class)->use(RefreshDatabase::class)->in('Feature')`. Custom expectations: `expect()->extend('toBeOne', ...)`. Test files use `test('description', function () {...})`. Use `route('name')` for URLs. Assert with `$response->assertOk()`.
