---
paths:
  - 'app/Concerns/*.php'
---

# Concerns

## Reusable Validation Traits
`App\Concerns` directory contains reusable validation traits. `ProfileValidationRules` provides `profileRules(?int $userId)`, `nameRules()`, `emailRules(?int $userId)`. `PasswordValidationRules` provides `passwordRules()`, `currentPasswordRules()`. Use `Rule::unique(User::class)->ignore($userId)` for update validation.
