---
paths:
  - 'database/factories/*.php'
---

# Factories

## Factory Conventions
Factories extend `Factory<Model>` with generic type: `/** @extends Factory<User> */`. Use `protected static ?string $password` for shared password. State methods return `static`. Use `fn (array $attributes) => [...]` for state callbacks. Include common states: `unverified()`, `withTwoFactor()`.
