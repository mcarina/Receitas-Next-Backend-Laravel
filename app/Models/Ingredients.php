<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredients extends Model
{
    use HasFactory;
    protected $table = 'ingredients';

    protected $fillable = [
        'name',
        'amount',
        'recipe_id',
        'created_at',
        'updated_at',
    ];    

    // Relação com a tabela User
    public function recipes()
    {
        return $this->belongsTo(Recipes::class, 'recipe_id'); // 'recipe_id' é a chave estrangeira
    }

}
