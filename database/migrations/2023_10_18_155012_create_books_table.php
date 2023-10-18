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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('image')->nullable();
			$table->enum('status', ['close', 'open', 'download'])->default('open');
			$table->text('description')->nullable();
            $table->integer('book_count')->default(0);
            $table->boolean('live')->default(1);
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
