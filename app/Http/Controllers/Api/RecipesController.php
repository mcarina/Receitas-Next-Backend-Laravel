<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipes;
use App\Models\Ingredients;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use Illuminate\Support\Facades\DB;

class RecipesController extends Controller
{
    public function index(): JsonResponse
    {
        $recipes = Recipes::with(['category', 'user', 'ingredients'])->orderBy('id', 'ASC')->paginate(6);
    
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
                'time' => $validatedData['time'], // Adiciona o tempo de preparo
                'type_time' => $validatedData['type_time'], // Adiciona o tipo de tempo
                'porcoes' => $validatedData['porcoes'], // Adiciona o número de porções
                'status' => $validatedData['status'] ?? 'ativo', // Define o status com valor padrão 'ativo' se não for fornecido
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
    
    public function update(StoreRecipeRequest $request, $id): JsonResponse
    {
        try {
            // Buscar a receita a ser atualizada
            $recipe = Recipes::findOrFail($id);
    
            // Atualizar os campos específicos, se forem passados
            $recipe->update([
                'title' => $validatedData['title'] ?? $recipe->title,
                'description' => $validatedData['description'] ?? $recipe->description,
                'preparation_method' => $validatedData['preparation_method'] ?? $recipe->preparation_method,
                'category_id' => $validatedData['category_id'] ?? $recipe->category_id,
                'time' => $validatedData['time'] ?? $recipe->time,
                'type_time' => $validatedData['type_time'] ?? $recipe->type_time,
                'porcoes' => $validatedData['porcoes'] ?? $recipe->porcoes,
                'status' => $validatedData['status'] ?? $recipe->status,
            ]);
    
            // Caso os ingredientes sejam fornecidos, associá-los à receita
            if (isset($validatedData['ingredients'])) {
                foreach ($validatedData['ingredients'] as $ingredientData) {
                    Ingredients::updateOrCreate(
                        ['recipe_id' => $recipe->id, 'name' => $ingredientData['name']], // Buscar ou criar
                        ['amount' => $ingredientData['amount']] // Atualizar ou criar o valor de 'amount'
                    );
                }
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Recipe updated successfully!',
            ], 200);
    
        } catch (\Exception $e) {
            // Em caso de erro, retorna um erro genérico
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function search(Request $request)
    {
        $query = $request->get('query');
    
        if (!$query) {
            return response()->json([]);
        }
    
        $recipes = Recipes::where('title', 'LIKE', '%' . $query . '%')
            ->orWhere('description', 'LIKE', '%' . $query . '%')
            ->limit(10) // Limite para 10 sugestões
            ->orderBy('title', 'ASC') // Ordenação por título
            ->get();
    
        return response()->json($recipes);
    }

    public function destroy(Recipes $recipe): JsonResponse
    {
        DB::beginTransaction();  // Iniciando transação

        try {
            $recipe->delete(); // Deletando 

            DB::commit(); // Confirmando a transação

            return response()->json([
                'receita' => $recipe,
                'message' => "receita apagada!",
            ], 201);

        } catch (Exception $e) {

            DB::rollback(); // Falha na operação, rollback na transação

            return response()->json([
                'status' => false,
                'message' => "Falha ao apagar receita",
            ], 400);
        }
    }

}
