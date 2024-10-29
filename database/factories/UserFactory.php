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
        $plainPassword = Str::random(10);

        return [
            'username' => 'Нияз',
            'surname' => 'Гатауллин',
            'patronymic' => 'Рамилевич',
            'login' => 'k1z1n',
            'logo' => null,
            'email' => null,
            'email_verified_at' => null,
            'password' => Hash::make($plainPassword),
            'pp' => $plainPassword,
            'role' => 'admin',
            'group_id' => null,
            'remember_token' => Str::random(10),
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
