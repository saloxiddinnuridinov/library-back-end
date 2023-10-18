<?php

namespace Database\Seeders;

use App\Models\RentBook;
use Illuminate\Database\Seeder;

class RentBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\RentBook::factory()->count(20)->create();
    }
}
