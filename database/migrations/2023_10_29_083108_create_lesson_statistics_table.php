<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lesson_statistics', function (Blueprint $table) {
            $table->id();
			$table->integer('start_time');
			$table->integer('end_time');
			$table->foreign('lesson_id')->references('id')->on('departments');
			
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('lesson_statistics');
    }
};
