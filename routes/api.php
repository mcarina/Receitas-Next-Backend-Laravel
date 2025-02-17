<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RecipesController;
use App\Http\Controllers\Api\SavedController;

//login e logout
Route::post('/login', [LoginController::class, 'login']);
//Cadastro de usuários
Route::post('/users',[UserController::class, 'store']);
// todas as receitas
Route::get('/recipes', [RecipesController::class, 'index']);
// campo de pesquise
Route::get('/search-recipes', [RecipesController::class, 'search']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    //logout
    Route::post('/logout', [LoginController::class, 'logout']);

    /**
    User Controller
     */
    // Visualizar usuários
    Route::get('/users', [UserController::class, 'index']);
    //Atualizar dados do usuário
    Route::put('/users/{user}',[UserController::class, 'update']);
    // info do usuario
    Route::get('/user-info', [UserController::class, 'showInfo']);
    //vizualizar usuários por id
    Route::get('/users/{user}', [UserController::class, 'show']);
    //Apaga um usuário
    Route::delete('/users/{user}',[UserController::class, 'destroy']);

    /**
    Recipes Controller
     */
    Route::post('/recipes', [RecipesController::class, 'store']);
    Route::get('/recipes/{id}', [RecipesController::class, 'show']);
    Route::delete('/recipes/{recipe}', [RecipesController::class, 'destroy']);
    // Route::put('/recipes/{id}',[RecipesController::class, 'update']);

    /**
    Saved Controller
     */
    Route::get('/save-recipe', [SavedController::class, 'index']);
    Route::post('/save-recipe', [SavedController::class, 'saveRecipe']);

});