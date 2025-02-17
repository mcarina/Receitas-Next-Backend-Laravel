<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bucket_img', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com usuário
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade'); // Relacionamento com receita
            $table->string('path'); // Caminho da imagem no MinIO/S3
            $table->string('url'); // URL pública da imagem
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bucket_img');
    }
};
