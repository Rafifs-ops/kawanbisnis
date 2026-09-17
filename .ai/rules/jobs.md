---
paths:
  - 'app/Jobs/*.php'
---

# Jobs

## Job Implementation Pattern
Jobs implement `ShouldQueue`. Use traits: `Dispatchable, InteractsWithQueue, Queueable, SerializesModels`. Properties: `$tries = 3`, `$timeout = 300`. Constructor receives model via promotion: `public GrowthDiagnosis $diagnosis`. Handle method orchestrates multi-agent AI pipeline with sequential agent calls.
