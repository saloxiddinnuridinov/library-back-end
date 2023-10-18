<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [

			'name' => $this->faker->word,
			'surname' => $this->faker->word,
			'email' => $this->faker->word,
			'password' => $this->faker->word,
			'image' => $this->faker->word,
			'qr_code' => $this->faker->word,
			'group' => $this->faker->word,
			'role' => 'student',

        ];
    }
}
