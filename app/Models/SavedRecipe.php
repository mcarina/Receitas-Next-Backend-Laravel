<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedRecipe extends Model
{
    use HasFactory;

    protected $table = 'save_recipes'; // Nome da tabela no banco
    protected $fillable = [
        'user_id', 
        'recipe_id',
        'created_at',
        'updated_at',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com Recipe
    public function recipe()
    {
        return $this->belongsTo(Recipes::class);
    }
}