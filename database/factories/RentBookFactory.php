<?php

namespace Database\Factories;

use App\Models\RentBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentBookFactory extends Factory
{
    protected $model = RentBook::class;

    public function definition()
    {
        return [
			
			
			
			
			
			'is_taken' => $this->faker->boolean,
			
			

        ];
    }
}
