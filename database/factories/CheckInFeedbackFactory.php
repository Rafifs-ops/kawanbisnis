<?php

namespace Database\Factories;

use App\Models\ActionPlan;
use App\Models\CheckInFeedback;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CheckInFeedback>
 */
class CheckInFeedbackFactory extends Factory
{
    protected $model = CheckInFeedback::class;

    public function definition(): array
    {
        return [
            'action_plan_id' => ActionPlan::factory(),
            'checkin_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'actual_result' => ['result' => fake()->paragraph()],
            'kpi_achieved' => ['metric' => fake()->word(), 'achieved' => fake()->boolean()],
            'learning_notes' => fake()->paragraph(),
        ];
    }
}
