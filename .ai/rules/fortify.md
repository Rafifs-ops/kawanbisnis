---
paths:
  - 'app/Actions/Fortify/*.php'
---

# Fortify

## Fortify Action Validation Pattern
Fortify actions use traits from `App\Concerns` for reusable validation: `PasswordValidationRules` and `ProfileValidationRules`. Validation via `Validator::make($input, [...])->validate()`. Use spread operator for composed rules: `...$this->profileRules()`. Return type hints for User model.
