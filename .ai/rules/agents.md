---
paths:
  - 'app/AI/Agents/*.php'
---

# Agents

## AI Agent Implementation Pattern
Agents implement `Agent`, `HasStructuredOutput`, `HasTools` interfaces. Use `Promptable` trait. Must define: `instructions()` (system prompt in Bahasa Indonesia), `tools()` (return SimilaritySearch instances), `schema(JsonSchema $schema): array` (structured output), `buildPrompt(): string`. Constructor receives domain objects (BusinessSnapshot, array findings). Use `AgentKnowledge::findSimilar()` for RAG.
