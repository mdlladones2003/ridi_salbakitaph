<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name'        => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'phone_number'      => '0917' . fake()->numberBetween(1000000, 9999999),
            'reputation_score' => fake()->numberBetween(0, 100),
            'role'              => 'user',
            'is_verified'       => fake()->boolean(70),
            'is_active'         => true,
            'last_active_at'    => now()->subDays(rand(0, 30)),
            'remember_token'    => Str::random(10)
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null
        ]);
    }
}
