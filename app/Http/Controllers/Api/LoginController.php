<?php

namespace App\Http\Controllers\Api;

use \App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;


class LoginController extends Controller

{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password", example="*********")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login bem-sucedido"
     *     ),
     * )
     *
    Rota de Login**/

    public function login(Request $request): JsonResponse
    {
        // Validar o email e a senha fornecidos
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Recuperar os dados do usuário autenticado
            $user = Auth::user();

            // Criar um token de autenticação para o usuário
            $token = $user->createToken('api-token')->plainTextToken;

            // Retornar uma resposta de sucesso com o token
            return response()->json([
                'status' => true,
                'token' => $token,
            ], 201);
        } else {
            // Autenticação falhou, retornar uma resposta de erro
            return response()->json([
                'status' => false,
                'message' => 'Email ou senha incorreta'
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     @OA\Response(
     *         response=201,
     *         description="Logout bem-sucedido"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
    destroi o token de acesso do usuário.**/

    public function logout(): JsonResponse
    {
        try {
            $user = Auth::user();


            if ($user) {

                $user->tokens()->delete();

                return response()->json([
                    'status' => true,
                    'message' => "Deslogado!",
                ], 201);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Usuário não autenticado.",
                ], 401); // Retorna 401 se o usuário não estiver autenticado
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Erro ao sair: " . $e->getMessage(),
            ], 404);
        }
    }
}