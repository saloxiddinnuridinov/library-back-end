<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [

			'name' => $this->faker->lastName,
			'surname' => $this->faker->firstName,
			'email' => $this->faker->email,
			'password' => Hash::make('admin123'),
			'image' => 'https://photogov-com.akamaized.net/examples/original/DE.webp',
			'qr_code' => $this->faker->word,
			'group' => 'AX-20-07',
			'role' => 'student',

        ];
    }
}
