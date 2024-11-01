<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recipes')->insert([
            [
                'title' => 'Pancakes',
                'description' => 'Delicious fluffy pancakes served with syrup.',
                'category_id' => 1, // Exemplo: ID da categoria 'Breakfast'
                'user_id' => 1,     // Exemplo: ID do usuário autor da receita
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Caesar Salad',
                'description' => 'Classic Caesar salad with romaine, croutons, and parmesan.',
                'category_id' => 2, // Exemplo: ID da categoria 'Lunch'
                'user_id' => 1,     // Exemplo: ID do usuário autor da receita
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);      

    }
}
