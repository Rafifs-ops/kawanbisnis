<?php

namespace App\Models;

use Database\Factories\ActionPlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $growth_diagnosis_id
 * @property string $title
 * @property string $description
 * @property int $priority_rank
 * @property float $priority_score
 * @property int $timeline_days
 * @property array{metric?: string, unit?: string}|null $target_kpi
 * @property list<array{day_offset: int, title: string, description: string, date?: string}>|null $steps
 * @property string $approval_status
 * @property-read GrowthDiagnosis $growthDiagnosis
 * @property-read Collection<int, CheckInFeedback> $checkInFeedbacks
 */
#[Fillable([
    'growth_diagnosis_id',
    'title',
    'description',
    'priority_rank',
    'priority_score',
    'timeline_days',
    'target_kpi',
    'steps',
    'approval_status',
])]
class ActionPlan extends Model
{
    /** @use HasFactory<ActionPlanFactory> */
    use HasFactory;

    protected $casts = [
        'target_kpi' => 'array',
        'steps' => 'array',
        'priority_score' => 'float',
        'priority_rank' => 'integer',
        'timeline_days' => 'integer',
    ];

    /** @return BelongsTo<GrowthDiagnosis, $this> */
    public function growthDiagnosis(): BelongsTo
    {
        return $this->belongsTo(GrowthDiagnosis::class);
    }

    /** @return HasMany<CheckInFeedback, $this> */
    public function checkInFeedbacks(): HasMany
    {
        return $this->hasMany(CheckInFeedback::class);
    }
}
