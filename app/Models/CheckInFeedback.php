<?php

namespace App\Models;

use Database\Factories\CheckInFeedbackFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $action_plan_id
 * @property string $checkin_date
 * @property array{result?: string}|null $actual_result
 * @property array{metric?: string, achieved?: bool}|null $kpi_achieved
 * @property string|null $learning_notes
 * @property-read ActionPlan $actionPlan
 */
#[Fillable([
    'action_plan_id',
    'checkin_date',
    'actual_result',
    'kpi_achieved',
    'learning_notes',
])]
class CheckInFeedback extends Model
{
    /** @use HasFactory<CheckInFeedbackFactory> */
    use HasFactory;

    protected $table = 'check_in_feedbacks';

    protected $casts = [
        'checkin_date' => 'date',
        'actual_result' => 'array',
        'kpi_achieved' => 'array',
    ];

    /** @return BelongsTo<ActionPlan, $this> */
    public function actionPlan(): BelongsTo
    {
        return $this->belongsTo(ActionPlan::class);
    }
}
