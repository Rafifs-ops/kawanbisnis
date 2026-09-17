<?php

namespace Database\Factories;

use App\Models\BusinessPassport;
use App\Models\GrowthGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GrowthGoal>
 */
class GrowthGoalFactory extends Factory
{
    protected $model = GrowthGoal::class;

    public function definition(): array
    {
        return [
            'business_passport_id' => BusinessPassport::factory(),
            'goal_type' => fake()->randomElement(['Increase Sales', 'Retention', 'AOV', 'Margin']),
            'target_metrics' => [
                'target' => fake()->numberBetween(1000000, 20000000),
                'unit' => 'nominal',
            ],
            'status' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function achieved(): static
    {
        return $this->state(fn () => ['status' => 'achieved']);
    }
}
