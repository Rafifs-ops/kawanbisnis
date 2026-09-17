---
paths:
  - 'app/Livewire/**/*.php'
---

# Livewire

## Livewire Component Patterns
Livewire components: 1) Use `#[Title('...')]` attribute for page titles. 2) `mount()` for initialization, `render(): View` for output. 3) Public properties for state. 4) `Flux::toast(variant: 'success', text: '...')` for notifications. 5) Components organized in subfolders: `Dashboard/`, `Settings/`, `Landing/`, `Actions/`. 6) Use `$this->validate([...])` for inline validation.

## Livewire Flux UI Integration
Use `Flux\Flux` facade for UI notifications: `Flux::toast(variant: 'success', text: '...')`. Variants: `success`, `error`. Blade templates use `flux:*` components. Livewire components reference views via `view('livewire.dashboard.overview')` naming convention matching directory structure.
