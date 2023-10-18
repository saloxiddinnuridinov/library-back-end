<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [

			'name' => $this->faker->word,
            'image' => 'https://clipart-library.com/2023/Black_Book_PNG_Clipart-1048.png',
			'status' => 'open',
			'description' => $this->faker->sentence,



        ];
    }
}
