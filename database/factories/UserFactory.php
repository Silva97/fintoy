<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $wallet = Wallet::factory()->create();

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_shopkeeper' => false,
            'identification_number' => $this->faker->numerify('###########'),
            'wallet_id' => $wallet->id,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }

    /**
     * Indicate that the user should be a shopkeeper
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function shopkeeper()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_shopkeeper' => true,
            ];
        });
    }
}
