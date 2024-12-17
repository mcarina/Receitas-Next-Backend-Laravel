<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipes;
use App\Models\Ingredients;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;

class RecipesController extends Controller
{
    public function index(): JsonResponse
    {
        $recipes = Recipes::with(['category', 'user', 'ingredients'])->orderBy('id', 'ASC')->paginate(5);
    
        $recipes->transform(function ($item) {
            $item->category = $item->category->category ?? 'N/A'; // nome da categoria
            $item->user_id = $item->user->name ?? 'N/A'; // nome do usuário
            
            // Adiciona os ingredientes ao item
            $item->ingredients = $item->ingredients->map(function ($ingredient) {
                return [
                    'name' => $ingredient->name,
                    'amount' => $ingredient->amount,
                ];
            });
    
            return $item;
        });
    
        return response()->json([
            'status' => true,
            'recipe' => $recipes,
        ], 200);
    }

    public function store(StoreRecipeRequest  $request): JsonResponse
    {
        $validatedData = $request->validated();
        $userId = Auth::id();

        // Verificar se já existe uma receita com o mesmo título e usuário
        $existingRecipe = Recipes::where('title', $validatedData['title'])
                            ->where('user_id', $userId)
                            ->first();

        if ($existingRecipe) {
            return response()->json([
                'message' => 'A recipe with this title already exists for this user.',
                'data' => $existingRecipe,
            ], 409);
        }

        // Criar a nova receita
        $recipe = Recipes::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'preparation_method' => $validatedData['preparation_method'],
            'category_id' => $validatedData['category_id'],
            'user_id' => $userId, 
        ]);

        // Associar os ingredientes à receita
        foreach ($validatedData['ingredients'] as $ingredientData) {
            Ingredients::create([
                'name' => $ingredientData['name'],
                'amount' => $ingredientData['amount'],
                'recipe_id' => $recipe->id,
            ]);
        }

        return response()->json([
            'message' => 'Recipe created successfully!',
            'data' => $recipe,
        ], 201);
    }
    
}
