---
paths:
  - 'app/Http/Controllers/Auth/*.php'
---

# Auth

## Socialite OAuth Controller Pattern
Google OAuth controller at `App\Http\Controllers\Auth\GoogleController`. Uses Indonesian comments. Pattern: 1) `redirectToGoogle()` returns Socialite redirect. 2) `handleGoogleCallback()` checks google_id first, then email, then creates new user. 3) OAuth users have `password => null`. 4) `Auth::login()` after finding/creating user. 5) Error handling with try/catch, redirect to login with flash message.
