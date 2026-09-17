<?php

namespace Database\Factories;

use App\Models\ActionPlan;
use App\Models\GrowthDiagnosis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActionPlan>
 */
class ActionPlanFactory extends Factory
{
    protected $model = ActionPlan::class;

    public function definition(): array
    {
        return [
            'growth_diagnosis_id' => GrowthDiagnosis::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priority_rank' => fake()->numberBetween(1, 3),
            'priority_score' => fake()->randomFloat(1, 1, 10),
            'timeline_days' => fake()->randomElement([7, 14, 30]),
            'target_kpi' => ['metric' => fake()->word()],
            'approval_status' => 'pending',
        ];
    }
}
