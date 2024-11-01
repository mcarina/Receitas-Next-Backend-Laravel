<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('ingredients_id');
            $table->unsignedBigInteger('user_id'); // ou $table->unsignedBigInteger('author');

            $table->timestamps();

            // Definindo as chaves estrangeiras (foreign keys)
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('ingredients_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // ou $table->foreign('author')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
