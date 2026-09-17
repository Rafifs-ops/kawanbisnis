---
paths:
  - 'app/Models/*.php'
  - app/Models/User.php
  - app/Models/AgentKnowledge.php
---

# Models

## Use Laravel 13 Attribute-based Fillable and Hidden
Models use `#[Fillable([...])]` and `#[Hidden([...])]` PHP attributes instead of `$fillable` and `$hidden` properties. Import from `Illuminate\Database\Eloquent\Attributes\Fillable` and `Illuminate\Database\Eloquent\Attributes\Hidden`. User model example: `#[Fillable(['name', 'email', 'email_verified_at', 'password', 'google_id'])]`.

## Model Casts Use Method Syntax
Use `protected function casts(): array` method (not property) for type casting. User model uses method syntax: `protected function casts(): array { return [...]; }`. Other models use property syntax `protected $casts = [...]` — follow whichever pattern exists in the file you're editing.

## Model Relationship PHPDoc Types
All relationship methods must have PHPDoc return types with generic parameters: `/** @return HasOne<BusinessPassport, $this> */`, `/** @return BelongsTo<User, $this> */`, `/** @return HasMany<BusinessSnapshot, $this> */`. Use `$this` for the parent model type. Factory import uses `/** @use HasFactory<ModelFactory> */` annotation above the `use` statement.

## User Model - OAuth + Passkey Support
User model implements `MustVerifyEmail` and `PasskeyUser` (from Laravel\Fortify). Uses `PasskeyAuthenticatable` and `TwoFactorAuthenticatable` traits. `google_id` column is nullable for OAuth users. Password is nullable for OAuth-only users (set via GoogleController). Methods: `businessPassport(): HasOne`, `initials(): string`.

## JSON Column Structure Convention
JSON columns follow typed structure patterns. BusinessPassport: `products` → `list<array{name: string, price: float, margin: float}>`, `sales_channels` → `list<string>`, `constraints` → `array{marketing_budget?: int, team_capacity?: int}`. BusinessSnapshot: `new_vs_returning_customers` → `array{new?: int, returning?: int}`, `product_performances` → `list<array{name: string, revenue: float, orders: int}>`. Document in PHPDoc `@property` annotations.

## Embeddings and Vector Search Pattern
AgentKnowledge model uses `Laravel\Ai\Embeddings` for vector generation. `generateEmbedding()` calls `Embeddings::for([$this->content])->generate()`. `findSimilar()` implements in-memory cosine similarity (works with SQLite). Threshold: similarity > 0.3. Embedding dimension: 1024 (matches OpenRouter config). Uses `agent_type` field to scope knowledge per agent.
