---
paths:
  - 'database/migrations/*.php'
---

# Migrations

## Migration Conventions
Migrations use anonymous class syntax: `return new class extends Migration`. Timestamps in filename: `YYYY_MM_DD_HHMMSS_*.php`. Use `$table->foreignId('user_id')->constrained()->cascadeOnDelete()`. JSON columns for complex data: `$table->json('products')->nullable()`. Include comments for JSON structure examples: `// [{name, price, margin}]`.
