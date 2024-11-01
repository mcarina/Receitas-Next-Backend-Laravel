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
        Schema::table('ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('recipe_id')->nullable()->after('id'); // Adiciona a coluna recipe_id

            // Definindo a chave estrangeira (foreign key)
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']); // Remove a chave estrangeira
            $table->dropColumn('recipe_id'); // Remove a coluna
        });
    }
};
