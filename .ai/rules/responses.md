---
paths:
  - 'app/AI/Responses/*.php'
---

# Responses

## AI Response DTO Pattern
Response classes are simple DTOs with: 1) Constructor-promoted public properties. 2) `schema(JsonSchema $schema): array` method returning structured output definition. 3) PHPDoc `@param` annotations for complex array types. 4) No methods beyond schema. Used by agents for typed AI responses.
