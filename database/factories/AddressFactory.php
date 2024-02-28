<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'line address 1' => $this->faker->address,
            'line address 2' => $this->faker->address,
            'type' => $this->faker->randomElement(['home', 'work', 'other']),
            'addressable_id' => User::factory()->create()->id, // Example: creating a user and using its ID
            'addressable_type' => User::class, // Example: relating to the User model
        ];
    }
}
