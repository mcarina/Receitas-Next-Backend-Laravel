<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipes;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RecipesController extends Controller
{
    public function index(): JsonResponse
    {
        $recipes = Recipes::with(['category', 'user', 'ingredients'])->orderBy('id', 'ASC')->paginate(20);
    
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
    
}
