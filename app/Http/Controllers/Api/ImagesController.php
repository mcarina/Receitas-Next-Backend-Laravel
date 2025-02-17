<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipes;
use App\Models\BucketImg;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImagesController extends Controller
{
    public function upload(Request $request, $recipe_id): JsonResponse
    {
        try{
            // Validação
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB máximo
            ]);

            $user = auth()->user(); // Pega o usuário autenticado
            if (!$user) {
                return response()->json(['error' => 'Usuário não autenticado'], 401);
            }

            // Verifica se a receita existe
            $recipe = Recipes::find($recipe_id);
            if (!$recipe) {
                return response()->json(['error' => 'Receita não encontrada'], 404);
            }

            // Obtém a imagem do request
            $bucket = env('AWS_BUCKET');
            $file = $request->file('file');
            
            // Define um nome único para o arquivo baseado no timestamp e nome original
            $fileName = $bucket . '/' . time() . '_' . $file->getClientOriginalName();
            
            // Faz o upload para o MinIO (S3)
            $path = Storage::disk('s3')->put($fileName, $file);
            Log::info('Upload path returned: ' . $path); 
            if (!$path) {
                return response()->json(['error' => 'Erro ao fazer upload da imagem'], 500);
            }
            $url = Storage::disk('s3')->url($fileName); // Gera a URL pública

            // Salva no banco
            $bucketImg = BucketImg::create([
                'user_id' => $user->id,
                'recipe_id' => $recipe_id, // Usando o ID da URL
                'path' => $fileName,
                'url' => $url
            ]);

            return response()->json([
                'message' => 'Imagem enviada com sucesso!',
                'image' => $bucketImg
            ]);


        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao processar a solicitação', 'message' => $e->getMessage()], 500);
        }
    }

}