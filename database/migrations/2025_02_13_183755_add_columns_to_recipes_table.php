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
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('time')->nullable(); // tempo de preparo em minutos
            $table->string('type_time')->nullable(); // tipo de tempo, ex: "minutos", "horas"
            $table->integer('porcoes')->nullable(); // número de porções
            $table->string('status')->default('ativo'); // status da receita, por exemplo: "ativo", "inativo"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['time', 'type_time', 'porcoes', 'status']);
        });
    }
};
