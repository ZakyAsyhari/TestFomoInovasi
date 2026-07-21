<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->ean13(),
            'name' =>  $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(10000, 500000),
            'stock' => $this->faker->randomNumber(2),
        ];
    }
}
