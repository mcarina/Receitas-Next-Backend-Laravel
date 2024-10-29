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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable(); 
            $table->string('address1')->nullable();  
            $table->string('city')->nullable();        
            $table->string('state', 2)->nullable();   
            $table->string('postal_code')->nullable();  
            $table->date('date_of_birth')->nullable(); 
            $table->string('cpf', 11)->nullable();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'address1', 'city', 'state', 'postal_code', 'date_of_birth', 'cpf']);
        });
    }
};
