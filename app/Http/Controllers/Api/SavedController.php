<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipes;
use App\Models\Ingredients;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SavedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Obter o usuário autenticado
        $user = auth()->user();
    
        // Obter as receitas salvas por esse usuário
        $savedRecipes = $user->savedRecipes()->with(['category', 'user', 'ingredients'])->orderBy('id', 'ASC')->paginate(6);
    
        $savedRecipes->transform(function ($item) {
            // Atribuindo categoria e nome do usuário
            $item->category = $item->category->category ?? 'N/A'; 
            $item->user_id = $item->user->name ?? 'N/A'; 
            
            // Adicionando os ingredientes ao item
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
            'saved_recipes' => $savedRecipes, // Retornando as receitas salvas
        ], 200);
    }
    
    
    public function saveRecipe(Request $request)
    {
        $user = auth()->user(); // Obtém o usuário autenticado
        $recipeId = $request->recipe_id;
    
        // Verifica se já está salvo
        if (!$user->savedRecipes()->where('recipe_id', $recipeId)->exists()) {
            $user->savedRecipes()->attach($recipeId);
            return response()->json(['message' => 'Receita salva com sucesso!']);
        }
    
        return response()->json(['message' => 'Receita já está nos favoritos!'], 400);
    }
    
    public function removeRecipe(Request $request)
    {
        $user = auth()->user();
        $recipeId = $request->recipe_id;
    
        $user->savedRecipes()->detach($recipeId);
    
        return response()->json(['message' => 'Receita removida dos favoritos!']);
    }
    

}
