# KawanBisnis

**AI Growth Team untuk UMKM** — A Laravel Livewire business platform that provides AI-powered growth diagnosis and actionable recommendations for small and medium businesses (UMKM).

## Table of Contents

- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Features](#features)
- [Getting Started](#getting-started)
- [Available Commands](#available-commands)
- [Routes & Endpoints](#routes--endpoints)
- [Page Structure](#page-structure)
- [AI System](#ai-system)
- [Database Schema](#database-schema)
- [Admin Panel](#admin-panel)

---

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend | Laravel | 13.17 |
| PHP | PHP | 8.3+ |
| Livewire | Livewire | 4.1 |
| UI Components | Flux UI | 2.13 |
| CSS | Tailwind CSS | 4 |
| Build Tool | Vite + vite-plus | 8 |
| Auth | Fortify | 1.37 |
| OAuth | Socialite | 5.31 |
| AI SDK | Laravel AI | 0.11.2 |
| Admin Panel | Filament | 5 |
| Testing | Pest | 5.1 |
| Static Analysis | Larastan | Level 7 |
| Code Style | Pint (laravel preset) | — |
| Database | SQLite (default), tests use `:memory:` | — |
| AI Provider | OpenRouter (text: `nex-agi/nex-n2.5-pro:free`, embeddings: `nvidia/nemotron-3-embed-1b:free`) | — |

### Extra Packages

- `spatie/laravel-medialibrary` — File/media management
- `spatie/laravel-tags` — Taggable models
- `laravel/chisel` — Laravel Boost integration

---

## Architecture

```
┌─────────────────────────────────────────────────────────┐
│                     BROWSER / CLIENT                     │
│  Flux UI Components · Alpine.js · Tailwind CSS · Vite    │
└──────────────────────────┬──────────────────────────────┘
                           │ HTTP
┌──────────────────────────▼──────────────────────────────┐
│                     LARAVEL APP                          │
│                                                         │
│  ┌─────────────┐  ┌──────────────┐  ┌───────────────┐  │
│  │   Fortify    │  │   Livewire   │  │   Filament    │  │
│  │  (Auth flow) │  │  (UI pages)  │  │  (Admin panel)│  │
│  └──────┬──────┘  └──────┬───────┘  └───────┬───────┘  │
│         │                │                   │           │
│  ┌──────▼────────────────▼───────────────────▼───────┐  │
│  │              Eloquent Models                      │  │
│  │  User · BusinessPassport · BusinessSnapshot        │  │
│  │  GrowthGoal · GrowthDiagnosis · ActionPlan          │  │
│  │  CheckInFeedback · AgentAnalysis · AgentKnowledge   │  │
│  └──────────────────────┬────────────────────────────┘  │
│                         │                               │
│  ┌──────────────────────▼────────────────────────────┐  │
│  │              AI Pipeline (Queued Job)              │  │
│  │  AnalyticsAgent → CustomerAgent → MarketingAgent   │  │
│  │  → StrategyAgent → Diagnosis + Action Plans        │  │
│  └──────────────────────┬────────────────────────────┘  │
│                         │                               │
│  ┌──────────────────────▼────────────────────────────┐  │
│  │          OpenRouter API (RAG + LLM)                │  │
│  │  AgentKnowledge (vector embeddings, 2048-dim)      │  │
│  └──────────────────────┬────────────────────────────┘  │
│                         │                               │
│  ┌──────────────────────▼────────────────────────────┐  │
│  │          Feedback Loop (Tandai Selesai)            │  │
│  │  CheckInFeedback → AgentKnowledge + Embedding      │  │
│  │  → Masuk ke knowledge base untuk siklus berikutnya │  │
│  └───────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

### Key Architectural Decisions

- **Livewire full-page components** for all dashboard and settings pages (rendered via `Route::livewire()`)
- **Fortify** handles all auth flows (login, register, 2FA, passkeys, email verification) with standard Blade views
- **Filament** runs as a separate admin panel at `/admin` with its own auth
- **No REST API** — the app is entirely server-rendered via Livewire + standard HTTP
- **Queued AI processing** — `ProcessGrowthDiagnosisJob` runs the multi-agent pipeline asynchronously
- **RAG (Retrieval-Augmented Generation)** — `AgentKnowledge` model stores vector embeddings for domain-specific AI context
- **Feedback Loop** — "Tandai Selesai" saves check-in feedback as `AgentKnowledge` with embedding, creating a growing knowledge base that improves future AI diagnoses

### Directory Structure

```
app/
├── Actions/Fortify/          # Fortify action classes (CreateNewUser, etc.)
├── AI/Agents/                # 4 specialized AI agents
│   ├── AnalyticsAgent.php
│   ├── CustomerAgent.php
│   ├── MarketingAgent.php
│   └── StrategyAgent.php
├── AI/Responses/             # Structured output DTOs
├── Concerns/                 # Shared traits
├── Console/                  # Artisan commands
├── Filament/Resources/       # Filament admin resources
├── Http/Controllers/Auth/    # Google OAuth controller
├── Jobs/                     # ProcessGrowthDiagnosisJob
├── Livewire/
│   ├── Actions/              # Invokable action classes (Logout)
│   ├── Dashboard/            # Dashboard pages (6 components)
│   ├── Landing/              # Landing page
│   └── Settings/             # Settings pages (4 components)
├── Models/                   # 9 Eloquent models
└── Providers/                # Service providers

resources/
├── css/app.css               # Tailwind + brand tokens + animations
├── js/app.js                 # Alpine.js bootstrap
└── views/
    ├── components/           # Shared Blade components
    ├── flux/                 # Custom Flux UI overrides
    ├── layouts/              # 3 layout variants (sidebar, auth, guest)
    ├── livewire/             # Livewire component views
    └── partials/             # Head, settings headings
```

---

## Features

### Core Business

| Feature | Description |
|---------|-------------|
| **Business Passport** | Create and manage business profile (name, type, products, sales channels, constraints) |
| **Data Snapshots** | Input period-based business metrics (revenue, orders, AOV, customer breakdown, product performance) |
| **Growth Goals** | Set growth targets (Increase Sales, Retention, AOV, Margin) with status tracking |
| **AI Growth Diagnosis** | Multi-agent AI analysis producing structured diagnosis with root causes, opportunities, and KPI targets |
| **Action Plans** | AI-generated prioritized action items with detailed step-by-step scheduling (day-specific dates), timelines (7-day / 30-day), and KPI targets. Grouped by diagnosis for history tracking |
| **Tandai Selesai** | Mark action plans as completed, report actual results, KPI achievement, and learning notes. Feedback is automatically saved as AI knowledge (RAG) for future analysis |

### Authentication

| Feature | Description |
|---------|-------------|
| **Email/Password** | Standard registration with email verification |
| **Google OAuth** | One-click login via Google (Socialite) |
| **Two-Factor Auth** | TOTP-based 2FA with QR code, manual setup key, recovery codes |
| **Passkeys (WebAuthn)** | Passwordless login via FIDO2 passkeys |
| **Password Reset** | Email-based password reset flow |

### Admin Panel

| Feature | Description |
|---------|-------------|
| **Agent Knowledge Management** | CRUD for AI knowledge base entries with embedding generation |
| **Type Badge System** | Color-coded agent types (analytics, customer, marketing, strategy) |
| **RAG Embedding Generation** | Generate vector embeddings from admin panel for RAG system |

### Design

- Dark mode by default with brand color system (`kb-blue-electric`, `kb-blue-light`, `kb-black-blue`)
- Custom CSS animations: scroll-reveal, word-reveal, gradient text, glassmorphism, hover-lift
- Responsive design with mobile sidebar
- Respects `prefers-reduced-motion`

---

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 22+
- SQLite (default) or another supported database

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd kawanbisnis

# Run the full setup (installs deps, generates key, migrates, builds assets)
composer setup
```

The `composer setup` command runs:

```bash
composer install
cp .env.example .env        # if .env doesn't exist
php artisan key:generate
php artisan migrate --force
npm install
npm run build
```

### Environment Configuration

Copy `.env.example` to `.env` and configure:

```env
# Database (SQLite by default)
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Google OAuth (optional)
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback

# AI / OpenRouter
OPENROUTER_API_KEY=your-openrouter-key
```

### Start Development Server

```bash
composer dev
```

This starts 3 processes concurrently via `npx concurrently`:

| Process | Command | Purpose |
|---------|---------|---------|
| `server` | `php artisan serve` | Laravel HTTP server |
| `queue` | `php artisan queue:listen --tries=1 --timeout=0` | Queue worker for AI jobs |
| `vite` | `npm run dev` | Vite dev server for assets |

---

## Available Commands

### Composer Scripts

| Command | Description |
|---------|-------------|
| `composer setup` | Full project setup (install, key, migrate, npm build) |
| `composer dev` | Start server + queue + vite concurrently |
| `composer test` | Run full CI: `config:clear` → `lint:check` → `types:check` → `test` |
| `composer lint` | Fix code style (Pint parallel) |
| `composer lint:check` | Check code style without fixing (Pint dry run) |
| `composer types:check` | Run static analysis (PHPStan level 7) |

### Testing

```bash
# Run all tests
php artisan test

# Run a specific test file
php artisan test tests/Feature/ExampleTest.php

# Run tests matching a filter
php artisan test --filter=testName

# Run via Pest directly
vendor/bin/pest tests/Feature/ExampleTest.php

# Create a new test
php artisan make:test --pest SomeFeatureTest
```

### Code Quality

```bash
# Auto-fix PHP code style (run after any edit)
vendor/bin/pint --dirty --format agent

# Check PHPStan level 7 analysis
vendor/bin/phpstan analyse
```

---

## Routes & Endpoints

This application does **not** have a REST API. All routes are web routes serving Livewire pages or handling form submissions.

### Public Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| `GET` | `/` | `home` | Landing page |
| `GET` | `/auth/google` | `auth.google` | Redirect to Google OAuth |
| `GET` | `/auth/google/callback` | — | Google OAuth callback handler |
| `GET` | `/up` | — | Health check endpoint |
| `GET` | `/.well-known/passkey-endpoints` | `well-known.passkeys` | Passkey discovery (JSON) |

### Authenticated Routes (requires `auth` + `verified` middleware)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| `GET` | `/dashboard` | `dashboard` | Dashboard overview |
| `GET` | `/dashboard/passport` | `passport.index` | Business Passport management |
| `GET` | `/dashboard/snapshot/create` | `snapshot.create` | Create data snapshot + growth goal |
| `GET` | `/dashboard/diagnosis/{growthDiagnosis}` | `diagnosis.show` | View AI diagnosis results |
| `GET` | `/dashboard/action-plan` | `action-plan.index` | List all action plans |
| `GET` | `/dashboard/check-in/{actionPlan}/create` | `check-in.create` | Mark action plan as completed and report results |

### Settings Routes

| Method | URI | Name | Description | Middleware |
|--------|-----|------|-------------|-----------|
| `GET` | `/settings` | — | Redirect to `/settings/profile` | `auth` |
| `GET` | `/settings/profile` | `profile.edit` | Edit profile (name, email) | `auth` |
| `GET` | `/settings/appearance` | `appearance.edit` | Theme settings | `auth`, `verified` |
| `GET` | `/settings/security` | `security.edit` | Password, 2FA, passkeys | `auth`, `verified`, `password.confirm` |

### Fortify Auth Routes (auto-registered)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| `GET` | `/login` | `login` | Login page |
| `POST` | `/login` | `login.store` | Handle login (throttle: 5/min) |
| `POST` | `/logout` | `logout` | Handle logout |
| `GET` | `/register` | `register` | Registration page |
| `POST` | `/register` | `register.store` | Handle registration |
| `GET` | `/forgot-password` | `password.request` | Forgot password page |
| `POST` | `/forgot-password` | `password.email` | Send reset link |
| `GET` | `/reset-password/{token}` | `password.reset` | Reset password page |
| `POST` | `/reset-password` | `password.update` | Handle password reset |
| `GET` | `/email/verify` | `verification.notice` | Email verification prompt |
| `GET` | `/email/verify/{id}/{hash}` | `verification.verify` | Verify email address |
| `POST` | `/email/verification-notification` | `verification.send` | Resend verification email |
| `PUT` | `/user/profile-information` | `user-profile-information.update` | Update profile info |
| `PUT` | `/user/password` | `user-password.update` | Update password |
| `GET` | `/user/confirm-password` | `password.confirm` | Password confirmation page |
| `POST` | `/user/confirm-password` | `password.confirm.store` | Confirm password |
| `GET` | `/two-factor-challenge` | `two-factor.login` | 2FA challenge page |
| `POST` | `/two-factor-challenge` | `two-factor.login.store` | Verify 2FA code (throttle: 5/min) |
| `POST` | `/user/two-factor-authentication` | `two-factor.enable` | Enable 2FA |
| `DELETE` | `/user/two-factor-authentication` | `two-factor.disable` | Disable 2FA |
| `GET` | `/user/two-factor-qr-code` | `two-factor.qr-code` | Get 2FA QR code |
| `GET` | `/user/two-factor-recovery-codes` | `two-factor.recovery-codes` | Get recovery codes |
| `POST` | `/user/two-factor-recovery-codes` | `two-factor.regenerate-recovery-codes` | Regenerate recovery codes |

### Passkey Routes (via Fortify + Laravel Passkeys)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| `GET` | `/passkeys/login/options` | `passkey.login-options` | Get passkey login options (throttle: 10/min) |
| `POST` | `/passkeys/login` | `passkey.login` | Authenticate via passkey |
| `GET` | `/user/passkeys/options` | `passkey.registration-options` | Get passkey registration options |
| `POST` | `/user/passkeys` | `passkey.store` | Register a new passkey |
| `DELETE` | `/user/passkeys/{passkey}` | `passkey.destroy` | Delete a passkey |

### Filament Admin Panel Routes (auto-registered at `/admin`)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| `GET` | `/admin` | `filament.admin.home` | Admin dashboard |
| `GET` | `/admin/login` | `filament.admin.auth.login` | Admin login |
| `POST` | `/admin/logout` | `filament.admin.auth.logout` | Admin logout |
| `GET` | `/admin/agent-knowledges` | — | List agent knowledge entries |
| `GET` | `/admin/agent-knowledges/create` | — | Create agent knowledge entry |
| `GET` | `/admin/agent-knowledges/{record}/edit` | — | Edit agent knowledge entry |

### Livewire Internal Routes (auto-registered)

| Method | URI | Description |
|--------|-----|-------------|
| `POST` | `/livewire/update` | Livewire AJAX update handler |
| `POST` | `/livewire/upload-file` | File upload handler |
| `GET` | `/livewire/preview-file` | File preview handler |
| `GET` | `/livewire/livewire.js` | Livewire JavaScript asset |
| `GET` | `/flux/flux.js` | Flux UI JavaScript asset |

---

## Page Structure

### Layouts

| Layout | File | Usage |
|--------|------|-------|
| **Sidebar** | `resources/views/layouts/app/sidebar.blade.php` | All dashboard pages (primary layout) |
| **Header** | `resources/views/layouts/app/header.blade.php` | Alternate top-nav layout (available) |
| **Auth (Simple)** | `resources/views/layouts/auth/simple.blade.php` | All auth pages (centered, max-w-sm) |
| **Auth (Split)** | `resources/views/layouts/auth/split.blade.php` | Two-column auth (quote + form) |
| **Auth (Card)** | `resources/views/layouts/auth/card.blade.php` | Card-wrapped auth |
| **Guest** | `resources/views/layouts/guest.blade.php` | Landing page (minimal) |

### Page Hierarchy

```
PUBLIC PAGES (Guest Layout)
├── Landing Page (/)
│   ├── Hero section
│   ├── Problem Framework
│   ├── AI Team Preview
│   ├── How It Works (6-step flow)
│   ├── CTA
│   └── Footer

AUTH PAGES (Auth Layout — Simple)
├── Login (/login)
│   ├── Email/password form
│   ├── Google OAuth button
│   └── Passkey login button
├── Register (/register)
│   ├── Name/email/password form
│   └── Google OAuth button
├── Forgot Password (/forgot-password)
├── Reset Password (/reset-password/{token})
├── Verify Email (/email/verify)
├── Two-Factor Challenge (/two-factor-challenge)
└── Confirm Password (/user/confirm-password)

AUTHENTICATED PAGES (Sidebar Layout)
├── Dashboard (/dashboard)
│   ├── Stats cards
│   ├── Latest diagnosis preview
│   ├── Quick action cards
│   └── Action plan summary
├── Business Passport (/dashboard/passport)
│   ├── Business profile (name, type, description, target customer)
│   ├── Products (add/remove with name, price, margin)
│   ├── Sales channels (Instagram, Tokopedia, etc.)
│   └── Constraints (marketing budget, team capacity)
├── Snapshot Create (/dashboard/snapshot/create)
│   ├── Period selection
│   ├── Revenue & orders input
│   ├── Customer breakdown (new vs returning)
│   ├── Product performance data
│   └── Growth goal (type, target, unit)
├── Diagnosis Show (/dashboard/diagnosis/{id})
│   ├── Processing status (polling every 3s)
│   ├── Diagnosis summary
│   ├── Business diagnosis
│   ├── Root causes
│   ├── Key findings
│   ├── Opportunities
│   ├── Top 3 Recommendations (with step-by-step timeline)
│   ├── KPI metrics
│   └── Agent analyses (analytics, customer, marketing, strategy)
├── Action Plans (/dashboard/action-plan)
│   ├── Grouped by diagnosis (Diagnosis ke-1, ke-2, etc.)
│   ├── Each plan shows: title, priority %, timeline, steps timeline
│   ├── Steps with day-specific dates and status indicators
│   └── "Tandai Selesai" button per plan
└── Tandai Selesai (/dashboard/check-in/{id}/create)
    ├── Execution date
    ├── Actual results
    ├── KPI achievement
    ├── Learning notes
    └── Auto-saves to AgentKnowledge (RAG)

SETTINGS PAGES (Sidebar Layout + Settings Nav)
├── Profile (/settings/profile)
│   ├── Name / email edit
│   ├── Email verification status
│   └── Account deletion
├── Appearance (/settings/appearance)
│   └── Theme toggle (light/dark/system)
└── Security (/settings/security)
    ├── Password change
    ├── Two-factor authentication setup
    │   ├── QR code / manual setup key
    │   ├── Recovery codes
    │   └── Enable/disable toggle
    └── Passkey management (add/remove)
```

### Navigation

```
Sidebar (authenticated):
└── Platform
    └── Dashboard (/dashboard)

Settings nav (authenticated):
├── Profile (/settings/profile)
├── Security (/settings/security)
└── Appearance (/settings/appearance)

User menu (desktop sidebar footer / mobile header dropdown):
├── Settings (/settings/profile)
└── Log out
```

---

## AI System

### Multi-Agent Pipeline

The application implements a 4-agent AI system that analyzes business data asynchronously via `ProcessGrowthDiagnosisJob`:

```
BusinessSnapshot
    │
    ├─── AnalyticsAgent ─────────────┐
    │    "Apa yang berubah dari      │
    │     data bisnis ini?"          │
    │                                │
    ├─── CustomerAgent ──────────────┤
    │    "Bagaimana perilaku         │
    │     pelanggan ini?"            │
    │                                │
    ├─── MarketingAgent ─────────────┤
    │    "Channel mana yang paling   │
    │     efektif?"                  │
    │                                │
    ▼                                ▼
    StrategyAgent ◄── combined findings + RAG knowledge
    "Apa rekomendasi prioritas?"
         │
         ├── GrowthDiagnosis (6 sections)
         └── ActionPlan (up to 3 items, each with detailed steps + schedule)
              │
              ▼
    User executes steps → "Tandai Selesai"
         │
         └── CheckInFeedback → AgentKnowledge (RAG)
              └── Feeds back into next diagnosis cycle
```

### Agent Details

| Agent | Input | Output | Knowledge Type |
|-------|-------|--------|----------------|
| **AnalyticsAgent** | BusinessSnapshot | summary, anomalies[], key_findings[] | `analytics` |
| **CustomerAgent** | BusinessSnapshot | summary, segments[], insights[], hypotheses[] | `customer` |
| **MarketingAgent** | BusinessSnapshot + BusinessPassport | summary, channel_performance[], opportunities[] | `marketing` |
| **StrategyAgent** | Combined findings + constraints + today's date | business_diagnosis, root_causes[], growth_opportunity[], recommendations[] (with detailed steps + scheduling), action_plan, kpi_metrics[] | `strategy` |

#### StrategyAgent Recommendations Structure

Each recommendation includes a `steps` array with day-specific scheduling:

```
recommendations[] → {
  title, description, priority_score (1-10 → displayed as percentage),
  steps[] → {
    day_offset,     // 0 = today, 1 = tomorrow, etc.
    title,          // Step title
    description,    // Detailed actionable description
    date,           // Calculated date (YYYY-MM-DD) — added at save time
    day_name        // Localized day name (Senin, Selasa, etc.) — added at save time
  }
}
```

### RAG (Retrieval-Augmented Generation)

- `AgentKnowledge` model stores domain-specific knowledge per agent type
- Each entry has a 2048-dimensional vector embedding (generated via `nvidia/nemotron-3-embed-1b:free`)
- Similarity search uses cosine similarity with threshold > 0.3, top 3 results
- Embeddings can be generated from the admin panel via the "Generate Embedding" action
- **Feedback Loop**: When a user marks an action plan as completed ("Tandai Selesai"), the actual results, KPI achievement, and learning notes are automatically saved as `AgentKnowledge` (type: `strategy`) with embedding. This creates a growing knowledge base that improves future AI diagnoses

### AI Models

| Model | Provider | Use |
|-------|----------|-----|
| `nex-agi/nex-n2.5-pro:free` | OpenRouter | Text generation (agents) |
| `nvidia/nemotron-3-embed-1b:free` | OpenRouter | Embedding generation (RAG) |

---

## Database Schema

### Entity Relationships

```
User
 └─ HasOne ─ BusinessPassport
                ├─ HasMany ─ BusinessSnapshot
                └─ HasMany ─ GrowthGoal
                               └─ HasMany ─ GrowthDiagnosis
                                              ├─ HasMany ─ AgentAnalysis
                                              ├─ HasMany ─ ActionPlan
                                              │             └─ HasMany ─ CheckInFeedback
                                              └─ (JSON: summary_diagnosis, business_diagnosis,
                                                   root_causes, opportunities, kpi_metrics)

AgentKnowledge (standalone, with vector embeddings)
```

### Tables

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `users` | User accounts | `google_id` (nullable), `password` (nullable for OAuth) |
| `business_passports` | Business profiles | `business_name`, `business_type`, `products` (JSON), `sales_channels` (JSON), `constraints` (JSON) |
| `business_snapshots` | Period business data | `period_start`, `period_end`, `revenue`, `total_orders`, `average_order_value`, `new_vs_returning_customers` (JSON), `product_performances` (JSON) |
| `growth_goals` | Business targets | `goal_type`, `target_metrics` (JSON), `status` |
| `growth_diagnoses` | AI diagnosis results | `summary_diagnosis`, `business_diagnosis`, `key_findings` (JSON), `root_causes` (JSON), `opportunities` (JSON), `kpi_metrics` (JSON) |
| `agent_analyses` | Individual agent outputs | `agent_type`, `findings` (JSON), `hypotheses` (JSON), `confidence_score` |
| `action_plans` | Recommended actions | `title`, `description`, `priority_rank`, `priority_score`, `timeline_days`, `target_kpi` (JSON), `steps` (JSON: day_offset, title, description, date, day_name), `approval_status` |
| `check_in_feedbacks` | Progress check-ins | `checkin_date`, `actual_result` (JSON), `kpi_achieved` (JSON), `learning_notes` |
| `agent_knowledges` | RAG knowledge base | `agent_type`, `title`, `content`, `embedding` (JSON, 2048-dim) |
| `passkeys` | WebAuthn passkeys | `user_id`, `credential_id` (unique), `credential` (JSON) |
| `media` | Media library files | `model_type`, `model_id`, `collection_name`, `file_name` |
| `tags` / `taggables` | Spatie tags | Tag system with JSON name/slug |
| `sessions` | User sessions | Laravel session storage |
| `jobs` / `job_batches` / `failed_jobs` | Queue system | Database-backed queue |
| `cache` / `cache_locks` | Application cache | Laravel cache store |

---

## Admin Panel

The Filament 5 admin panel runs at `/admin` with its own authentication.

### Access

- URL: `http://localhost:8000/admin`
- Login: Separate from main app auth
- Dark mode: Enabled by default

### Resources

#### AgentKnowledge Resource

Manage the AI knowledge base entries used for RAG:

- **Navigation**: "Pengetahuan Agen" (`/admin/agent-knowledges`)
- **Table**: Agent type (color-coded badge), title, content, created_at
- **Form**: Agent type (select), title, content (textarea)
- **Actions**:
  - Standard CRUD (list, create, edit, delete)
  - **Generate Embedding** — Generate vector embedding for RAG on any entry

### Agent Type Badges

| Type | Badge Color |
|------|-------------|
| `analytics` | Blue (info) |
| `customer` | Green (success) |
| `marketing` | Yellow (warning) |
| `strategy` | Red (danger) |

---

## CI/CD

GitHub Actions workflow (`.github/workflows/tests.yml`):

- **Triggers**: Push to `main`, all pull requests
- **PHP version**: 8.5
- **Node version**: 22
- **Steps**:
  1. Checkout code
  2. Setup PHP (8.5) + Composer
  3. Setup Node (22)
  4. `composer setup`
  5. `composer ci:check` (lint → types → tests)
