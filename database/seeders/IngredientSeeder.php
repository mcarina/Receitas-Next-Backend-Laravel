<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredients')->insert([
            ['name' => 'Salt', 'recipe_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pepper', 'recipe_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
