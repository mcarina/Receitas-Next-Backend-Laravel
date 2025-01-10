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
            $item->category = $item->category->category ?? 'N/A'; 
            $item->user_id = $item->user->name ?? 'N/A'; 
            
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

    public function store(StoreRecipeRequest $request): JsonResponse
    {
        try {
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
                'status' => true,
                'message' => 'Recipe created successfully!',
            ], 201);
    
        } catch (\Exception $e) {
            // Em caso de erro, retorna um erro genérico
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function show(Recipes $id): JsonResponse
    {
        try {
            // Tentando encontrar a receita com os relacionamentos
            $recipe = Recipes::with(['category', 'user', 'ingredients'])->findOrFail($id->id);
        
            // Processando os dados da categoria e usuário
            $recipe->category = $recipe->category->category ?? 'N/A';
            $recipe->user_id = $recipe->user->name ?? 'N/A';
        
            // Adicionando os ingredientes ao item
            $recipe->ingredients = $recipe->ingredients->map(function ($ingredient) {
                return [
                    'name' => $ingredient->name,
                    'amount' => $ingredient->amount,
                ];
            });
        
            // Retorna a resposta com os dados da receita
            return response()->json([
                'status' => true,
                'recipe' => $recipe,
            ], 200);
        } catch (\Exception $e) {
            // Captura qualquer exceção que ocorra e retorna um erro apropriado
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar a receita: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    
}
