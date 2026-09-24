<?php

namespace Database\Factories;

use App\Models\GrowthDiagnosis;
use App\Models\GrowthGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GrowthDiagnosis>
 */
class GrowthDiagnosisFactory extends Factory
{
    protected $model = GrowthDiagnosis::class;

    public function definition(): array
    {
        return [
            'growth_goal_id' => GrowthGoal::factory(),
            'status' => 'completed',
            'summary_diagnosis' => fake()->paragraph(),
            'key_findings' => [fake()->sentence()],
            'root_causes' => [fake()->sentence()],
            'opportunities' => [fake()->sentence()],
        ];
    }
}
