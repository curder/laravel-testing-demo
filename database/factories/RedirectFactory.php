<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RedirectFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'from' => parse_url($this->faker->unique()->url(), PHP_URL_PATH),
            'to' => parse_url($this->faker->unique()->url(), PHP_URL_PATH),
        ];
    }
}
