<?php

namespace Database\Factories;

use App\Models\BusinessPassport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BusinessPassport>
 */
class BusinessPassportFactory extends Factory
{
    protected $model = BusinessPassport::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'business_name' => fake()->company(),
            'business_type' => fake()->randomElement(['F&B', 'Fashion', 'Retail', 'Service']),
            'target_customer' => fake()->randomElement(['Mahasiswa 18-24 thn', 'Ibu Rumah Tangga 25-40 thn', 'Profesional 25-45 thn']),
            'business_description' => fake()->sentence(),
            'products' => [
                ['name' => fake()->word(), 'price' => fake()->numberBetween(10000, 500000), 'margin' => fake()->numberBetween(10, 50)],
            ],
            'sales_channels' => fake()->randomElements(['Store', 'Instagram', 'Tokopedia', 'Shopee', 'WhatsApp'], 2),
            'constraints' => [
                'marketing_budget' => fake()->numberBetween(500000, 5000000),
                'team_capacity' => fake()->numberBetween(1, 10),
            ],
        ];
    }
}
