---
paths:
  - config/ai.php
  - config/fortify.php
---

# Config

## AI Configuration - OpenRouter Default
Default AI provider is `openrouter` for all modalities (text, images, audio, transcription, embeddings, reranking). OpenRouter models: text default `inclusionai/ling-3.0-flash-vl:free`, embeddings default `liquid/lfm-2.5-embedding-350m:free` with 1024 dimensions. All providers configured via env vars.

## Fortify Features Configuration
Enabled features: `registration()`, `resetPasswords()`, `emailVerification()`, `twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true])`, `passkeys(['confirmPassword' => true])`. Home path: `/dashboard`. Username: `email`. Lowercase usernames enabled. Passkey config uses `parse_url(config('app.url'), PHP_URL_HOST)` for relying party ID.
