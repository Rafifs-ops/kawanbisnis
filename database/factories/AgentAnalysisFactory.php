<?php

namespace Database\Factories;

use App\Models\AgentAnalysis;
use App\Models\GrowthDiagnosis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgentAnalysis>
 */
class AgentAnalysisFactory extends Factory
{
    protected $model = AgentAnalysis::class;

    public function definition(): array
    {
        return [
            'growth_diagnosis_id' => GrowthDiagnosis::factory(),
            'agent_type' => fake()->randomElement(['analytics', 'customer', 'marketing', 'strategy']),
            'question_answered' => fake()->sentence(),
            'findings' => ['summary' => fake()->paragraph()],
            'hypotheses' => [fake()->sentence()],
            'confidence_score' => fake()->randomFloat(2, 0.3, 0.99),
        ];
    }
}
