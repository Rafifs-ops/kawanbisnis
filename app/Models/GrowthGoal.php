<?php

namespace App\Models;

use Database\Factories\GrowthGoalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $business_passport_id
 * @property string $goal_type
 * @property array{target?: float, unit?: 'nominal'|'percent'}|null $target_metrics
 * @property string $status
 * @property-read BusinessPassport $businessPassport
 * @property-read Collection<int, GrowthDiagnosis> $growthDiagnoses
 */
#[Fillable([
    'business_passport_id',
    'goal_type',
    'target_metrics',
    'status',
])]
class GrowthGoal extends Model
{
    /** @use HasFactory<GrowthGoalFactory> */
    use HasFactory;

    protected $casts = [
        'target_metrics' => 'array',
    ];

    /** @return BelongsTo<BusinessPassport, $this> */
    public function businessPassport(): BelongsTo
    {
        return $this->belongsTo(BusinessPassport::class);
    }

    /** @return HasMany<GrowthDiagnosis, $this> */
    public function growthDiagnoses(): HasMany
    {
        return $this->hasMany(GrowthDiagnosis::class);
    }
}
