<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('surname');
			$table->string('email');
			$table->string('password');
			$table->string('image')->nullable();
			$table->longText('qr_code')->nullable();
			$table->string('group')->nullable();
			$table->enum('role', ['student', 'admin'])->default('student');
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
        });

        DB::table('users')->insert([
            'name' => 'Salohiddin',
            'surname' => 'Nuridinov',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
