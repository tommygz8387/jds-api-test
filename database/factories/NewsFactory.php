<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'photo' => fake()->unique()->asciify('********************').'.jpg',
            'content' => fake()->sentence(10),
            'user_id' => fake()->randomDigitNotNull(),
        ];
    }
}
