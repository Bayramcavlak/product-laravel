<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 0, 999999.99),
            'status' => $this->faker->randomElement(['active', 'inactive', 'out_of_stock', 'pre_order', 'coming_soon', 'discontinued']),
            'user_id' => $this->faker->numberBetween(1, 10),
            'type' => $this->faker->randomElement(['mal', 'hizmet']),
        ];
    }
}
