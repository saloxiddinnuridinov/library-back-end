<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [

			'name' => $this->faker->word,
			'image' => 'https://legacy-www.math.harvard.edu/sciencecenter2013/m-1071.jpg',
			'description' => $this->faker->sentence,
			'phone' => $this->faker->word,



        ];
    }
}
