<?php

namespace Database\Factories;

use App\Models\AgentKnowledge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgentKnowledge>
 */
class AgentKnowledgeFactory extends Factory
{
    protected $model = AgentKnowledge::class;

    public function definition(): array
    {
        return [
            'agent_type' => fake()->randomElement(['analytics', 'customer', 'marketing', 'strategy']),
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'embedding' => null,
        ];
    }
}
