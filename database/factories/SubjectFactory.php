<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition()
    {
        return [
			'department_id' => Department::all()->random()->id,
			'name' => $this->faker->word,
			'tag' => $this->faker->word,
			'position' => $this->faker->numerify,
			'description' => $this->faker->sentence,



        ];
    }
}
