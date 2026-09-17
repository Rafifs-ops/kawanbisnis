<?php

namespace App\Models;

use Database\Factories\AgentAnalysisFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $growth_diagnosis_id
 * @property string $agent_type
 * @property string $question_answered
 * @property array{summary?: string, anomalies?: list<string>, key_findings?: list<string>, channel_performance?: list<string>, segments?: list<string>, insights?: list<string>, opportunities?: list<string>}|null $findings
 * @property list<string>|null $hypotheses
 * @property float $confidence_score
 * @property-read GrowthDiagnosis $growthDiagnosis
 */
#[Fillable([
    'growth_diagnosis_id',
    'agent_type',
    'question_answered',
    'findings',
    'hypotheses',
    'confidence_score',
])]
class AgentAnalysis extends Model
{
    /** @use HasFactory<AgentAnalysisFactory> */
    use HasFactory;

    protected $casts = [
        'findings' => 'array',
        'hypotheses' => 'array',
        'confidence_score' => 'float',
    ];

    /** @return BelongsTo<GrowthDiagnosis, $this> */
    public function growthDiagnosis(): BelongsTo
    {
        return $this->belongsTo(GrowthDiagnosis::class);
    }
}
