<?php

namespace Database\Factories;

use App\Models\BusinessPassport;
use App\Models\BusinessSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BusinessSnapshot>
 */
class BusinessSnapshotFactory extends Factory
{
    protected $model = BusinessSnapshot::class;

    public function definition(): array
    {
        $revenue = fake()->numberBetween(1000000, 50000000);
        $totalOrders = fake()->numberBetween(50, 500);

        return [
            'business_passport_id' => BusinessPassport::factory(),
            'period_start' => now()->subMonths(3)->startOfMonth(),
            'period_end' => now()->subMonth()->endOfMonth(),
            'revenue' => $revenue,
            'total_orders' => $totalOrders,
            'average_order_value' => $totalOrders > 0 ? round($revenue / $totalOrders, 2) : 0,
            'new_vs_returning_customers' => [
                'new' => fake()->numberBetween(20, 100),
                'returning' => fake()->numberBetween(10, 80),
            ],
            'product_performances' => [
                ['name' => 'Product A', 'quantity' => fake()->numberBetween(10, 100), 'revenue' => fake()->numberBetween(500000, 5000000)],
                ['name' => 'Product B', 'quantity' => fake()->numberBetween(5, 50), 'revenue' => fake()->numberBetween(200000, 2000000)],
            ],
        ];
    }
}
