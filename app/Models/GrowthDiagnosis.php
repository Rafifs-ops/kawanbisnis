<?php

namespace App\Models;

use Database\Factories\GrowthDiagnosisFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $growth_goal_id
 * @property string $summary_diagnosis
 * @property string|null $business_diagnosis
 * @property list<string>|null $key_findings
 * @property list<string>|null $root_causes
 * @property list<string>|null $opportunities
 * @property list<array{metric: string, target: string, unit: string}>|null $kpi_metrics
 * @property-read GrowthGoal $growthGoal
 * @property-read Collection<int, AgentAnalysis> $agentAnalyses
 * @property-read Collection<int, ActionPlan> $actionPlans
 */
#[Fillable([
    'growth_goal_id',
    'summary_diagnosis',
    'business_diagnosis',
    'key_findings',
    'root_causes',
    'opportunities',
    'kpi_metrics',
])]
class GrowthDiagnosis extends Model
{
    /** @use HasFactory<GrowthDiagnosisFactory> */
    use HasFactory;

    protected $casts = [
        'key_findings' => 'array',
        'root_causes' => 'array',
        'opportunities' => 'array',
        'kpi_metrics' => 'array',
    ];

    /** @return BelongsTo<GrowthGoal, $this> */
    public function growthGoal(): BelongsTo
    {
        return $this->belongsTo(GrowthGoal::class);
    }

    /** @return HasMany<AgentAnalysis, $this> */
    public function agentAnalyses(): HasMany
    {
        return $this->hasMany(AgentAnalysis::class);
    }

    /** @return HasMany<ActionPlan, $this> */
    public function actionPlans(): HasMany
    {
        return $this->hasMany(ActionPlan::class);
    }
}
