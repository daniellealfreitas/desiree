<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       // Curitiba como ponto central
        $baseLat = -25.4297;
        $baseLng = -49.2719;

        // Gera pequenas variações (±0.005 ~ 500m)
        $lat = $baseLat + $this->faker->randomFloat(7, -0.005, 0.005);
        $lng = $baseLng + $this->faker->randomFloat(7, -0.005, 0.005);

        return [
            'name' => $this->faker->name(),
            'username'=>$this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // senha padrão
            'remember_token' => Str::random(10),
            'latitude' => $lat,
            'longitude' => $lng,
            'created_at' => now(),
            'updated_at' => now(),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
